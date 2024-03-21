<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationPaymentDetailResource extends JsonResource
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
            'reservation_id' => $this->reservation_id,
            'payment_method' => $this->payment_method,
            'payment_status' => $this->payment_status,
            'paid_amount' => $this->paid_amount,
            'status' => $this->status,
        ];
    }
}
