<?php

namespace App\Http\Requests\Room;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [
            'name' => 'required|string|max:255',
            'room_type_id' => 'required',
            'description' => 'required',
            'number_of_guests' => 'required',
            'counter' => 'required|integer|min:0',
            'room_size' => 'required|string',
            'breakfirst_included' => 'required|boolean',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'price_amt' => 'required|numeric|min:0',
            'net_services_amt' => 'nullable|numeric|min:0',
            'total_amt' => 'required|numeric|min:0',
            'room_bed_types' => 'nullable',

            'room_other_images.*.img' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'room_services.*.service' => 'nullable',
            'room_services.*.description' => 'nullable',
            'room_services.*.price' => 'nullable|numeric|min:0',

            'status' => 'required|boolean',
        ];
    }
}
