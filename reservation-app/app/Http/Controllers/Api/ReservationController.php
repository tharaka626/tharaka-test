<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\StoreReservationFrontRequest;
use App\Http\Requests\Reservation\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use App\Models\Reservation;
use App\Models\ReservationPaymentDetail;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReservationResource::collection(Reservation::with('reservation_details','payment_details','services','user')->paginate(10));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        try {
            DB::beginTransaction();

            $services = [];
            $total_services_amt = 0;

            //push services
            foreach ($request->reservation_services as $key => $value) {
                array_push($services, [
                    'service_id' => $value['service'],
                    'description' => $value['description'],
                    'price' => $value['price'],
                ]);
                $total_services_amt += (float)$value['price'];
            }

            $reservation = Reservation::create([
                'user_id' => $request->validated()['user_id'],
                'check_in_date' => date('Y-m-d', strtotime($request->validated()['check_in_date'])),
                'check_out_date' => date('Y-m-d', strtotime($request->validated()['check_out_date'])),
                'additional_request' => $request->validated()['additional_request'],
                'number_of_guests' => $request->validated()['number_of_guests'],
                'payment_status' => $request->validated()['payment_status'],
                'sub_amount' => $request->validated()['sub_amount'],
                'net_services_amt' => $total_services_amt,
                'discount' => $request->validated()['discount'],
                'vat' => $request->validated()['vat'],
                'net_amount' => ((float)$request->validated()['sub_amount'] + $total_services_amt + $request->validated()['vat']) - $request->validated()['discount'],
                'total_paid_amount' => $request->validated()['total_paid_amount'],
                'status' => $request->validated()['status'],
            ]);

            //save room services
            foreach ($services as $key => $value) {
                $reservation->services()->attach($value['service_id'], [
                    'description' => $value['description'],
                    'price' => $value['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($request->reservation_details as $key => $value) {
                $reservation->reservation_details()->attach($value['room'], [
                    'additional_request' => $value['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $room = Room::find($value['room']);
                $room->update([
                    'used_count' => (int)$room->used_count + 1
                ]);
            }

            if ($request->reservation_payment_detials[0]['paid_amount'] != null) {
                $arr = [];

                foreach ($request->reservation_payment_detials as $key => $value) {
                    $payments = new ReservationPaymentDetail([
                        'payment_method' => $value['payment_method'],
                        'payment_status' => $value['payment_status'],
                        'paid_amount' => $value['paid_amount'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    array_push($arr, $payments);
                }

                $reservation->payment_details()->saveMany($arr);
            }

            
            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new ReservationResource($reservation)
                ],
                'message' => 'Reservation created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

     /**
     * Store a newly created resource in storage.
     */
    public function front_save(StoreReservationFrontRequest $request)
    {
        try {
            DB::beginTransaction();

            $reservation = Reservation::create([
                'user_id' => auth()->user()->id,
                'check_in_date' => date('Y-m-d', strtotime($request->validated()['check_in_date'])),
                'check_out_date' => date('Y-m-d', strtotime($request->validated()['check_out_date'])),
                'additional_request' => $request->validated()['additional_request'],
                'number_of_guests' => $request->validated()['number_of_guests'],
                'payment_status' => 0,
                'status' => 1,
            ]);

    

            foreach ($request->reservation_details as $key => $value) {
                $reservation->reservation_details()->attach($request->validated()['room_id'], [
                    'additional_request' => "",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $room = Room::find($value['room']);
                $room->update([
                    'used_count' => (int)$room->used_count + 1
                ]);
            }

           

            
            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new ReservationResource($reservation)
                ],
                'message' => 'Reservation created successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        try {
            DB::beginTransaction();
            $back_reservations = $reservation;
            $reservation->update(['status' => $reservation->status ? 0 : 1]);

            if ($back_reservations->status == 1) {
                foreach ($back_reservations->reservation_details as $key => $value) {
                    # code...
                    $room = Room::find($value->id);
                    $room->update([
                        'used_count' => (int)$room->used_count + 1
                    ]);
                }
            } else {
                foreach ($back_reservations->reservation_details as $key => $value) {
                    # code...
                    $room = Room::find($value->id);
                    $room->update([
                        'used_count' => (int)$room->used_count - 1
                    ]);
                }
            }

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new ReservationResource($reservation)
                ],
                'message' => $reservation->status ? 'Requested reservation activated successfully' :'Requested reservation disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
