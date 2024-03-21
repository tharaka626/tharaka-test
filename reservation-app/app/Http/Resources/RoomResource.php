<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'room_type_id' => $this->room_type_id,
            'description' => $this->description,
            'number_of_guests' => $this->number_of_guests,
            'used_count' => $this->used_count,
            'total_count' => $this->total_count,
            'room_size' => $this->room_size,
            'breakfirst_included' => $this->breakfirst_included,
            'main_image' => asset('storage/rooms/'.$this->main_image),
            'price_amt' => number_format($this->price_amt, 0),
            'price_amt_decimal' => $this->price_amt,
            'net_services_amt' => number_format($this->net_services_amt, 0),
            'net_services_amt_decimal' => $this->net_services_amt,
            'total_amt' => number_format($this->total_amt, 0),
            'total_amt_decimal' => $this->total_amt,
            'status' => $this->status,

            'additional_request' => $this->whenPivotLoaded('reservation_details', function () {
                return $this->pivot->additional_request;
            }),

            'services' => ServiceResource::collection($this->whenLoaded('services')),
            'bed_types' => BedTypeResource::collection($this->whenLoaded('bed_types')),
            'other_images' => RoomOtherImageResource::collection($this->whenLoaded('other_images')),
            'room_type' => new RoomTypeResource($this->whenLoaded('room_type'))
        ];
    }
}
