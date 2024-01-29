<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class Payin extends BaseModel
{
    use HasFactory;

    /** @var string */
    protected $table = 'payins';

    protected $fillable = [
        'id',
        'payment_code',
        'input',
        'amount',
        'status_id',
        'payment_request_id',
    ];

    /**
     * Bank Account Relation
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function bank_account(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class, 'bank_account_id', 'id');
    }

    /**
     * Payment Request Relation
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function payment_request(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    /**
     * Status of the payin.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(PayinStatus::class);
    }

    /**
     * Whether the payin is unprocessed.
     */
    public function getUnprocessedAttribute(): bool
    {
        return $this->status_id === PayinStatus::UNPROCESSED;
    }

    /**
     * Whether the payin has automatically processed.
     */
    public function getAutoAttribute(): bool
    {
        return $this->status_id === PayinStatus::AUTO;
    }

    /**
     * Whether the payin has manually processed.
     */
    public function getManualAttribute(): bool
    {
        return $this->status_id === PayinStatus::MANUAL;
    }

    /**
     * Whether the payin is blocked.
     */
    public function getBlockedAttribute(): bool
    {
        return $this->status_id === PayinStatus::BLOCKED;
    }

    /**
     * Whether the payin is alert.
     */
    public function getAlertAttribute(): bool
    {
        return $this->status_id === PayinStatus::ALERT;
    }

    /**
     * Whether the payin is closed.
     */
    public function getClosedAttribute(): bool
    {
        return $this->status_id === PayinStatus::CLOSED;
    }
}
