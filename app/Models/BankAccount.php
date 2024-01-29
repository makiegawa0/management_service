<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends BaseModel
{
    use SoftDeletes, HasFactory;

    /** @var string */
    protected $table = 'bank_accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bank_id',
        'account_name',
        'bank_name',
        'branch_name',
        'branch_number',
        'account_number',
        'disp_name',
        'is_active',
        'login_credentials',
    ];

    // /**
    //  * The "booting" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function boot(): void
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->password = bcrypt($model->password);
    //     });

    //     // static::updating(function ($model) {
    //     //     $model->password = bcrypt($model->password);
    //     // });

    //     // static::deleting(function (self $message) {
    //     //     $message->failures()->delete();
    //     // });
    // }

    /**
     * Information of the account.
     */
    public function bank_info(): BelongsTo
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function setLoginCredentialsAttribute(array $data): void
    {
        $this->attributes['login_credentials'] = encrypt(json_encode($data));
    }

    public function getLoginCredentialsAttribute(string $value): array
    {
        return json_decode(decrypt($value), true);
    }


    /**
     * Whether the bank is MUFG.
     */
    public function getMufgAttribute(): bool
    {
        return $this->bank_id === Bank::MUFG;
    }

    /**
     * Whether the bank is SMBC.
     */
    public function getSmbcAttribute(): bool
    {
        return $this->bank_id === Bank::SMBC;
    }

    /**
     * Whether the bank is RAKUTEN.
     */
    public function getRakutenAttribute(): bool
    {
        return $this->bank_id === Bank::RAKUTEN;
    }

    /**
     * Whether the bank is MIZUHO.
     */
    public function getMizuhoAttribute(): bool
    {
        return $this->bank_id === Bank::MIZUHO;
    }

    /**
     * Whether the bank is PAYPAY.
     */
    public function getPaypayAttribute(): bool
    {
        return $this->bank_id === Bank::PAYPAY;
    }
}
