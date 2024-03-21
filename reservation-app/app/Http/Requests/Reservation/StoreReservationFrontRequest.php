<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationFrontRequest extends FormRequest
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
            'room_id' => 'required',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'additional_request' => 'nullable',
            'number_of_guests' => 'required|integer|min:0',
            
        ];
    }
}
