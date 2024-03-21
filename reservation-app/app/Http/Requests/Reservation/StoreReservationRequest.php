<?php

namespace App\Http\Requests\Reservation;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'user_id' => 'required',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date',
            'additional_request' => 'nullable',
            'number_of_guests' => 'required|integer|min:0',
            'payment_status' => 'required|integer',
            'sub_amount' => 'required|numeric|min:0',
            'net_services_amt' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'vat' => 'nullable|numeric|min:0',
            'net_amount' => 'required|numeric|min:0',
            'total_paid_amount' => 'nullable|numeric|min:0',

            'reservation_details.*.room' => 'nullable',
            'reservation_details.*.description' => 'nullable',

            'reservation_payment_detials.*.payment_method' => 'nullable',
            'reservation_payment_detials.*.payment_status' => 'nullable',
            'reservation_payment_detials.*.paid_amount' => 'nullable|numeric|min:0',

            'reservation_services.*.service' => 'nullable',
            'reservation_services.*.description' => 'nullable',
            'reservation_services.*.price' => 'nullable|numeric|min:0',

            'status' => 'required|boolean',
        ];
    }
}
