<?php

namespace App\Services\PayIns;

use App\Facades\Slack;
use App\Models\BankAccount;
use App\Models\NewOrder;
use App\Models\NewUser;
use App\Repositories\PayInRepository;
use App\Repositories\PaymentRequestRepository;
use App\Services\Webhooks\CallbackWebhookService;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class PayInService
{
    /** @var PayInRepository */
    private $payinRepo;

    /** @var PaymentRequestRepository */
    private $paymentRequestRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PayInRepository $payinRepo, PaymentRequestRepository $paymentRequestRepository)
    {
        $this->payinRepo = $payinRepo;
        $this->paymentRequestRepository = $paymentRequestRepository;
    }

    /**
     * Update the payin data and return the updated data along with its corresponding payment request.
     *
     * @param int $id
     * @param array $data
     *
     * @return array
     *
     * @throws Exception
     */
    public function update(int $id, array $data)
    {
        $body = [];
        if (isset($data['order_process_code']) && $oPaymentRequest = NewOrder::where('order_process_code', $data['order_process_code'])->first()) {
            $body['callback_request_log_id'] = $id;
            $body['order_id'] = $oPaymentRequest->id;
            $body['created_at'] = $oPaymentRequest->created_at->format('Y-m-d H:i:s');
            $body['user_name'] = $oPaymentRequest->user->fname.' '.$oPaymentRequest->user->lname;
            $body['before_user_id'] = $oPaymentRequest->user->c_id;
            $body['cb_time'] = $oPaymentRequest->confirm_callback_at;
            $body['amount'] = number_format($oPaymentRequest->callback_amount).'('.number_format($oPaymentRequest->amount).')';
            $body['before_order_id'] = $oPaymentRequest->exp_id;
            $body['process_code'] = $oPaymentRequest->order_process_code;

            $data['order_id'] = $oPaymentRequest->id;
        }
        $payin = $this->payinRepo->update($id, $data);

        return $body;
    }

    /**
     * Identify type of user to decide how to process the new payin.
     *
     * @param string $paymentCode
     */
    // TODO: change the type fo method to private after changing the code in PayinWebhookService
    public static function identifyUserStatus(string $paymentCode): mixed
    {
        $sPaymentCodeWithoutServerName = str_replace(config('const.PAYMENT_CODE_PREFIX') ?? config('const.SERVER_UNIQUE_CODE').'-', '', $paymentCode);
        $subQeury = NewUser::where('process_code', $sPaymentCodeWithoutServerName);

        if ($subQeury->exists()) {
            if ($subQeury->onlyTrashed()->exists()) {
                return ['closed', $subQeury->first()];
            }

            if ($subQeury->where('alert_level', config('const.ALERT_USER_INFO_LEVEL_ALERT'))->exists()) {
                return ['alert', $subQeury->first()];
            }

            if ($subQeury->where('alert_level', config('const.ALERT_USER_INFO_LEVEL_BLOCK'))->exists()) {
                return ['blocked', $subQeury->first()];
            }
        } else {
            return ['unfound', $subQeury->first()];
        }

        return ['found', $subQeury->first()];
    }

    public function store($payin, $oBankAccount, $storePayment = true)
    {
        $values = self::identifyUserStatus($payin['process_code']);
        $status = $values[0];
        $oUser = $values[1];

        switch ($status) {
            case 'closed':
                $payment = $this->storePayment($oBankAccount, $oUser->id, $payin, config('const.INCOMING_LOG_STATUS_DELETED_USER'), $storePayment);
                break;

            case 'alert':
                $payment = $this->storePayment($oBankAccount, $oUser->id, $payin, config('const.INCOMING_LOG_STATUS_ALERT_USER'), $storePayment);
                break;

            case 'blocked':
                $payment = $this->storePayment($oBankAccount, $oUser->id, $payin, config('const.INCOMING_LOG_STATUS_BLOCK_USER'), $storePayment);
                break;

            case 'unfound':
                \Log::info($payment = $this->storePayment($oBankAccount, null, $payin, config('const.INCOMING_LOG_STATUS_ORDER_NONE'), $storePayment));
                break;

            default: // normal user
                if (!$storePayment) {
                    $payment = $this->storePayment($oBankAccount, $oUser->id, $payin, config('const.INCOMING_LOG_STATUS_SEND_OK'));
                    break;
                }
                if ($paymentRequest = $this->storePaymentAndItsRequest($oBankAccount, $oUser->id, $payin)) { // Note: ensure that payin data is not sent more than once to merchant when they failed to be stored due to an error
                    $payment = $this->notifyMerchantAndUpdate($paymentRequest, $oUser, $payin, $oBankAccount);
                }
        }
        return $payment;
    }

    private function storePaymentAndItsRequest($oBankAccount, $iUserId, $payin, $bIsManual = false)
    {
        try {
            DB::beginTransaction();
            $payment = $this->storePayment($oBankAccount, $iUserId, $payin, config('const.INCOMING_LOG_STATUS_SEND_OK'));

            $paymentRequest = $this->paymentRequestRepository->store([
                'order_process_code' => $payin['process_code'],
                'user_id' => $iUserId,
                'amount' => intval($payin['amount']),
                'exp_id' => Uuid::uuid4()->toString(),
                'status' => $bIsManual ? config('const.ORDER_STATUS_OK_VIA_PAYMENT_RECEIVED') : config('const.ORDER_STATUS_OK_VIA_REVERSE'),
                'cb_send_type' => config('const.NEW_ORDER_CB_SEND_TYPE_REVERSE'),
                'incoming_log_id' => $payment->id,
            ]);
            DB::commit();
            return $paymentRequest;
        } catch (\Exception $exception) {
            DB::rollback();
            \Log::error('Error storing payment and payment request ='.$exception->getMessage().' trace='.$exception->getTraceAsString());
            return null;
        }
    }

    /**
     * @throws Exception
     */
    private function storePayment(BankAccount $bankAccount, $userId, array $payin, int $status, bool $storePayment = true)
    {
        // TODO: turn amount to integer
        $paymentData = [
            'date' => $payin['date'],
            'process_code_org' => $payin['process_code_org'],
            'amount' => $payin['amount'],
            'remainder' => $payin['remainder'],
            'order_cid' => $userId,
            'order_process_code' => $payin['process_code'],
            'process_code' => $payin['process_code'],
            'mc' => config('const.PAYMENT_CODE_PREFIX') ?? config('const.SERVER_UNIQUE_CODE'),
            'bank_type' => $bankAccount->bank_info->nickname,
            'send_type' => $bankAccount->bank_info->nickname,
            'status' => $status,
            'deposit_manage_id' => $payin['deposit_manage_id'],
            'bank_id' => $bankAccount->id,
        ];
        \Log::info($bankAccount->id, $paymentData);

        return $storePayment ? $this->payinRepo->store($paymentData) : $paymentData;
    }

    private function notifyMerchantAndUpdate($paymentRequest, $oUser, $payin, $oBankAccount)
    {
        $aCallbackResult = CallbackWebhookService::sendData($paymentRequest, $oUser, $payin);

        if (isset($aCallbackResult['ok']) && $aCallbackResult['ok']) {
            $payin = $this->payinRepo->update($payin->id, ['order_id' => $paymentRequest->id]);
        } else {
            try {
                DB::beginTransaction();
                $this->paymentRequestRepository->update($paymentRequest->id, ['status' => config('const.ORDER_STATUS_CB_FAIL')]);
                $payin = $this->payinRepo->update($payin->id, ['status' => config('const.INCOMING_LOG_STATUS_SEND_FAILED')]);
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollback();
                \Log::error('Error updating payment id='.$payin->id.' and payment request id='.$paymentRequest->id.$exception->getMessage().' trace='.$exception->getTraceAsString());
            }

            $fields = [
                'Bank Info' => $oBankAccount,
                'PI Log' => $payin,
                'Response' => $aCallbackResult,
            ];
            $blocks = Slack::buildBlocks($fields);
            Slack::send('Receviecd unexpected callback response.', $blocks);
        }
        return $payin;
    }
}
