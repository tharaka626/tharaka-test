<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
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
            'user_id' => $this->user_id,
            'check_in_date' => $this->check_in_date,
            'check_out_date' => $this->check_out_date,
            'additional_request' => $this->additional_request,
            'number_of_guests' => $this->number_of_guests,
            'payment_status' => $this->payment_status,
            'sub_amount' => number_format($this->sub_amount, 0),
            'sub_amount_decimal' => $this->sub_amount,
            'net_services_amt' => number_format($this->net_services_amt, 0),
            'net_services_amt_decimal' => $this->net_services_amt,
            'discount' => number_format($this->discount, 0),
            'discount_decimal' => $this->discount,
            'vat' => number_format($this->vat, 0),
            'vat_decimal' => $this->vat,
            'net_amount' => number_format($this->net_amount, 0),
            'net_amount_decimal' => $this->net_amount,
            'total_paid_amount' => number_format($this->total_paid_amount, 0),
            'total_paid_amount_decimal' => $this->total_paid_amount,
            'status' => $this->status,

            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'reservation_details' => RoomResource::collection($this->whenLoaded('reservation_details')),
            'payment_details' => ReservationPaymentDetailResource::collection($this->whenLoaded('payment_details')),
            'user' => new DefaultResource($this->whenLoaded('user'))
        ];
    }
}
