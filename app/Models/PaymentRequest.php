<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Ramsey\Uuid\Uuid;

class PaymentRequest extends BaseModel
{
    use SoftDeletes, HasFactory;

    /** @var string */
    protected $table = 'payment_requests';

    protected $fillable = [
        'id',
        'user_code',
        'payment_request_unique_code',
        'amount',
        'payment_code',
        'status_id',
        'payin_id',
        'user_id',
        'callback_sent_at'
    ];

    /**
     * User relation.
     *
     * @return Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    /**
     * Status of the payment request.
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(PaymentRequestStatus::class);
    }

    /**
     * Payin this payment request is connected with.
     */
    public function payin(): BelongsTo
    {
        return $this->belongsTo(Payin::class, 'payin_id', 'id');
    }

    /**
     * Whether the payment request is unprocessed.
     */
    public function getUnprocessedAttribute(): bool
    {
        return $this->status_id === PaymentRequestStatus::UNPROCESSED;
    }

    /**
     * Whether the payment request has automatically processed.
     */
    public function getAutoAttribute(): bool
    {
        return $this->status_id === PaymentRequestStatus::AUTO;
    }

    /**
     * Whether the payment request has manually processed.
     */
    public function getManualAttribute(): bool
    {
        return $this->status_id === PaymentRequestStatus::MANUAL;
    }

    /**
     * Whether the payment request is failed.
     */
    public function getFailedAttribute(): bool
    {
        return $this->status_id === PaymentRequestStatus::FAILED;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                $model->payment_request_unique_code = Uuid::uuid4()->toString();
            }
        );
        // static::deleting(
        //     function (self $subscriber) {
        //         $subscriber->tags()->detach();
        //         $subscriber->messages()->each(static function (Message $message) {
        //             $message->failures()->delete();
        //         });
        //         $subscriber->messages()->delete();
        //     }
        // );
    }
}
