<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApiLog extends BaseModel
{
    use SoftDeletes, HasFactory;

    /** @var string */
    protected $table = 'api_logs';

    protected $fillable = [
        'id',
        'request',
        'response',
        'status_id',
        'payment_request_id',
    ];

    /**
     * Payment Request relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function payment_request(): BelongsTo
    {
        return $this->belongsTo(PaymentRequest::class);
    }

    /**
     * Status of the payment request.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(ApiLogStatus::class);
    }

    /**
     * Whether the payment request is unprocessed.
     */
    public function getPendingAttribute(): bool
    {
        return $this->status_id === ApiLogStatus::PENDING;
    }

    /**
     * Whether the payment request has automatically processed.
     */
    public function getConfirmedAttribute(): bool
    {
        return $this->status_id === ApiLogStatus::CONFIRMED;
    }
}
