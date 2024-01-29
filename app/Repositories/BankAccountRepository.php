<?php

namespace App\Repositories;

use App\Models\BankAccount;
use App\Models\Bank;

class BankAccountRepository extends BaseEloquentRepository
{
    protected $modelName = BankAccount::class;

    /**
     * @return mixed
     */
    public function getBankTypes()
    {
        return Bank::pluck('name', 'id')->toArray();
    }

    /**
     * @return void
     */
    public function unflagOtherAccountsIfFlagged(int $defaultFlg, int $bankId)
    {
        if ($defaultFlg) {
            $oAlreadyBankAccounts = $this->getBy([
                'bank_id' => $bankId,
                'is_active' => config('const.BANK_ACCOUNTS_is_active_ENABLED'),
            ]);
            foreach ($oAlreadyBankAccounts as $oBankAccount) {
                $oBankAccount->is_active = config('const.BANK_ACCOUNTS_is_active_DISABLED');
                $oBankAccount->save();
            }
        }
    }
}
