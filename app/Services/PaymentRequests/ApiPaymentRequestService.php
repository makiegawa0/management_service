<?php

namespace App\Services\PaymentRequests;

use App\Models\NewOrder;
use App\Models\PayinStatus;
use App\Models\PaymentRequest;
use App\Models\PaymentRequestStatus;
use App\Models\Setting;
use App\Repositories\PayInRepository;
use App\Repositories\PaymentRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ApiPaymentRequestService
{
    /** @var PaymentRequestRepository */
    private $paymentRequest;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentRequestRepository $paymentRequest)
    {
        // \Log::info('inside ApiPaymentRequestService');
        $this->paymentRequest = $paymentRequest;
    }

    public function enableReverseCB(Request $oRequests): bool
    {
        if (Setting::first()->reverse_cb) {
            // c_idやprocess_codeが分かる場合のみ、マニュアルリバースCB機能を表示
            if (Auth::user()->isAdmin()) {
                $oNewOrder = NewOrder::where('c_id', $oRequests->c_id)
                    ->orWhere('process_code', $oRequests->order_process_code)
                    ->first();
                if ($oNewOrder) {
                    return true;
                }
            }
        }

        return false;
    }

    public function show(): User
    {
        $oNewUsers = NewUser::search($oRequests)
            ->paginate(config('const.ADMIN_NEW_USER_DISPLAY_MAX'));

        $aFormItem = [
            'id',
            'start_id',
            'end_id',
            'c_id',
            'kyc',
            'user_kind',
            'keyword',
            'last_order_start_time',
            'last_order_end_time',
            'start_time',
            'end_time',
        ];

        $bSearchShow = false;
        foreach ($aFormItem as $sFormItem) {
            if ($oRequests->filled($sFormItem)) {
                $bSearchShow = true;
            }
        }

        $aBanks = [];
        $aTmpBanks = MtbBank::orderBy('sort_number', 'asc')->get();
        foreach ($aTmpBanks as $val) {
            $aBanks[$val['id']] = $val['name'];
        }

        $oTmpRequests = new Request();
        $oTmpRequests->merge([
            'order_column' => 'transfer_accounts.bank_id',
            'order_sort' => 'ASC',
        ]);
        $aAllBankList = [];
        $aTransferAccounts = TransferAccount::search($oTmpRequests)
            ->orderBy('bank_id', 'asc')
            ->get();
        foreach ($aTransferAccounts as $account) {
            $aAllBankList[$account->bank_id][$account->id] = $account->account_name;
        }

        $aUserIds = [];
        $aUserTransferAccounts = [];
        foreach ($oNewUsers as $oNewUser) {
            $aUserIds[] = $oNewUser->id;
        }
        $oUserTransfer = UserTransferAccount::whereIn('user_id', $aUserIds)->orderBy('bank_id', 'asc')->get();
        foreach ($oUserTransfer as $userTransfer) {
            $aUserTransferAccounts[$userTransfer->user_id][$userTransfer->bank_id] = $userTransfer->transfer_account_id;
        }

        $user = User::create($userData);

        $user->roles()->sync($userData['roles']);

        // More actions with that user: let's say, 5+ more lines of code
        // - Upload avatar
        // - Email to the user
        // - Notify admins about new user
        // - Create some data for that user
        // - and more...

        return $user;
    }

    public function store(array $data): ?PaymentRequest
    {
        $dataToStore = [];
        $payin = null;
        // \Log::info($data);
        // \Log::info(array_key_exists('amount', $data));
        if (array_key_exists('amount', $data)) {
            // payin does not exist
            $dataToStore['amount'] = $data['amount'];
        } else {
            // payin exists
            $payin = (new PayInRepository)->find($data['payin_id']);

            if ($payin) {
                $dataToStore['amount'] = $payin->amount;
                $dataToStore['payin_id'] = $payin->id;
            }
        }
        $dataToStore['status_id'] = PaymentRequestStatus::MANUAL;
        $dataToStore['callback_sent_at'] = now();
        $dataToStore['user_id'] = $data['user_id'];

        // TODO: send request (notify) to the merchant
        // if ok true not returned throw new RuntimeException("Unknown Mailgun webhook event type '{$eventName}'.");

        // \Log::info($dataToStore);
        $paymentRequest = null;
        DB::transaction(function () use ($dataToStore, $payin, &$paymentRequest) {
            $paymentRequest = (new PaymentRequestRepository)->store($dataToStore);
            
            if ($payin) {
                $payin->update([
                    'payment_request_id' => $paymentRequest->id,
                    'status_id' => PayinStatus::MANUAL
                ]);
            }
        });
        
        return $paymentRequest;

        // More actions with that user: let's say, 5+ more lines of code
        // - Upload avatar
        // - Email to the user
        // - Notify admins about new user
        // - Create some data for that user
        // - and more...
    }

    public function update(int $id, ?int $payin_id, bool $callback_succeeded = null): ?PaymentRequest
    {
        $paymentRequest = $this->paymentRequest->find($id);

        if ($callback_succeeded === true) {
            $payin = (new PayInRepository)->find($paymentRequest->payin_id);
            if ($payin && $payment_request_id = $this->checkIfPayinIsAlreadyLinked($payin->id)) {
                throw new RuntimeException("The payin ID: '{$$payin->id}' is already linked with '{$payment_request_id}' so could not link with '{$paymentRequest->id}'.");
            }
            DB::transaction(function () use ($payin, $paymentRequest) {
                $paymentRequest->update([
                    'amount' => $payin ? $payin->amount : $paymentRequest->amount,
                    'status_id' => PaymentRequestStatus::MANUAL
                ]);
                
                if ($payin) {
                    $payin->update([
                        'payment_request_id' => $paymentRequest->id,
                        'status_id' => PayinStatus::MANUAL
                    ]);
                }
                
            });
        } elseif ($callback_succeeded === false) {
            DB::transaction(function () use ($paymentRequest) {
                $paymentRequest->update([
                    'status_id' => PaymentRequestStatus::FAILED
                ]);
            });
        } else {
            if ($payin_id && $payment_request_id = $this->checkIfPayinIsAlreadyLinked($payin_id)) {
                throw new RuntimeException("The payin ID: '{$payin_id}' is already linked with '{$payment_request_id}' so could not link with '{$paymentRequest->id}'.");
            }
            DB::transaction(function () use ($payin_id, $paymentRequest) {
                $paymentRequest->update([
                    'payin_id' => $payin_id
                ]);
            });
        }
        
        return $paymentRequest;
    }

    private function checkIfPayinIsAlreadyLinked(int $payin_id): bool
    {
        if ($paymentRequest = $this->paymentRequest->findBy('payin_id', $payin_id)) {
            return $paymentRequest->id;
        }
        return false;
    }
}
