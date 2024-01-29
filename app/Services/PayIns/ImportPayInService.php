<?php

namespace App\Services\PayIns;

use App\Facades\Slack;
use App\Library\Common;
use App\Models\BankAccount;
use App\Models\BankType;
use App\Repositories\PayInRepository;
use Illuminate\Support\Facades\DB;

class ImportPayInService
{
    /** @var PayInRepository */
    private $payinRepo;

    /** @var PayInService */
    private $payinService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PayInRepository $payinRepo, PayInService $payinService)
    {
        $this->payinRepo = $payinRepo;
        $this->payinService = $payinService;
    }

    /**
     * @throws Exception
     */
    public function import(BankAccount $oBankAccount, array $data, array &$dateCounter): mixed
    {
        switch ($oBankAccount->bank_id) {
            case BankType::RAKUTEN:
                return $this->getRakutenData($oBankAccount, $data, $dateCounter);

            case BankType::MIZUHO:
                return $this->getMizuhoData($data);

            case BankType::PAYPAY:
                return $this->getPaypayData($data);

            default:
                return $this->getData($data);
        }
    }

    /**
     * Store payins.
     *
     * @param array $data
     */
    public function storeInBatch(array $data)
    {
        try {
            DB::beginTransaction();
            foreach ($data as $payin) {
                $this->payinRepo->store($payin);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            // Log::error('Admin | previewPost error:'.$e->getMessage());

            \Log::error('Error storing payments.'.$exception->getMessage().' trace='.$exception->getTraceAsString());

            return false;
        }
        return true;
    }

    /**
     * Process a payin from Rakuten.
     *
     * @param string $bank_id
     * @param array $data
     * @param array $dateCounter
     */
    private function getRakutenData(BankAccount $oBankAccount, array $data, &$dateCounter)
    {
        $data = array_values($data);
        $aPayIn = [];

        if ($data[1] > 0) {
            $aPayIn['date'] = Common::replaceDate($data[0]);
            $dateCounter[$aPayIn['date']] = ($dateCounter[$aPayIn['date']] ?? 0) + 1;

            $aPayIn['amount'] = intval($data[1]);
            $aPayIn['remainder'] = intval($data[2]);

            $aPayIn['process_code_org'] = $data[3];
            $aPayIn['process_code'] = self::filterPaymentCode($data[3]);

            $aPayIn['deposit_manage_id'] = str_replace('/', '', $aPayIn['date']).sprintf('%04d', $dateCounter[$aPayIn['date']]);

            if ($this->validateData($oBankAccount, $aPayIn)) {
                return $this->payinService->store($aPayIn, $oBankAccount, false);
            }
        }
        return null;
    }

    private static function filterPaymentCode($input)
    {
        $paymentCode = Common::toHankaku($input);

        $inputs = explode(' ', $paymentCode);
        foreach ($inputs as $input) {
            if (preg_match('/'.(config('const.PAYMENT_CODE_PREFIX') ?? config('const.SERVER_UNIQUE_CODE')).'-/', $input)) {
                // Remove other than alphabets or numbers
                return preg_replace('/[^A-Za-z0-9]/', '', $input);
            }
        }
        return '要チェック';
    }

    /**
     * Check if the new payin is already registered and if so, also check if there is any conflict between the new payin  and the existing metadata.
     *
     * @param BankAccount $oBankAccount
     * @param array $data
     */
    private function validateData($oBankAccount, $data): bool
    {
        if ($payin = $this->payinRepo->findByMany([
            'api_flg' => 1,
            'bank_id' => $oBankAccount->id,
            'deposit_manage_id' => $data['deposit_manage_id'],
        ])) {
            if (
                $data['date'] !== $payin['date'] ||
                $data['amount'] !== $payin['amount'] ||
                // $oAlreadyRegistData['process_code'] != $aVal['process_code'] ||
                $data['process_code_org'] !== $payin['process_code_org']
                // || $oAlreadyRegistData['remainder'] != $aVal['remainder']
            ) {
                $fields = [
                    'Bank Info' => $oBankAccount,
                    'New PI' => $data,
                    'Existing PI' => $payin,
                ];
                $blocks = Slack::buildBlocks($fields);
                Slack::send('New and existing data mismatch.', $blocks);

                return false;
            }
            return false;
        }

        return true;
    }
}
