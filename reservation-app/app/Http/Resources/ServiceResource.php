<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status,
            'description' => $this->whenPivotLoaded('room_services', function () {
                return $this->pivot->description;
            }),
            
            'rs_id' => $this->whenPivotLoaded('room_services', function () {
                return $this->pivot->id;
            }),
            'price' => $this->whenPivotLoaded('room_services', function () {
                return number_format($this->pivot->price, 0);
            }),
            'price_decimal' => $this->whenPivotLoaded('room_services', function () {
                return $this->pivot->price;
            }),

            'res_det_id' => $this->whenPivotLoaded('reservation_services', function () {
                return $this->pivot->id;
            }),
            'res_description' => $this->whenPivotLoaded('reservation_services', function () {
                return $this->pivot->description;
            }),
            'res_price' => $this->whenPivotLoaded('reservation_services', function () {
                return $this->pivot->price;
            }),
        ];
    }
}
