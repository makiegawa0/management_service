<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_request_unique_code' => $this->payment_request_unique_code,
            'amount' => $this->amount,
            'callback_sent_at' => $this->callback_sent_at,
            'status_id' => $this->status_id,
            'payin_id' => $this->payin_id,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->toDateTimeString()
        ];
    }
}
