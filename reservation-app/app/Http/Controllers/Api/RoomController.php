<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Room\StoreRoomRequest;
use App\Http\Requests\Room\UpdateRoomRequest;
use App\Http\Resources\RoomResource;
use App\Http\Resources\ServiceResource;
use App\Models\Room;
use App\Models\RoomOtherImage;
use App\Models\RoomService;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RoomResource::collection(Room::with('bed_types','services','other_images','room_type')->paginate(10));
    }

                /**
     * Display a listing of the resource.
     */
    public function active()
    {
        return RoomResource::collection(Room::with('bed_types','services','other_images','room_type')->where('status', 1)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomRequest $request)
    {
        try {
            DB::beginTransaction();

            if ($request->file('main_image')) {
                $file= $request->file('main_image');
                $filename= date('YmdHi').$file->getClientOriginalName();
                $file-> move(storage_path('app/public/rooms'), $filename);
            } else {
                $filename = null;
            }

            $services = [];
            $total_services_amt = 0;

            //push services
            foreach ($request->room_services as $key => $value) {
                array_push($services, [
                    'service_id' => $value['service'],
                    'description' => $value['description'],
                    'price' => $value['price'],
                ]);
                $total_services_amt += (float)$value['price'];
            }

            $room = Room::create([
                'name' => $request->validated()['name'],
                'room_type_id' => $request->validated()['room_type_id'],
                'description' => $request->validated()['description'],
                'number_of_guests' => $request->validated()['number_of_guests'],
                'total_count' => $request->validated()['counter'],
                'room_size' => $request->validated()['room_size'],
                'breakfirst_included' => $request->validated()['breakfirst_included'],
                'main_image' => $filename,
                'price_amt' => $request->validated()['price_amt'],
                'net_services_amt' => $total_services_amt,
                'total_amt' => (float)$request->validated()['price_amt'] + $total_services_amt,
                'status' => $request->validated()['status'],
            ]);

            //save room services
            foreach ($services as $key => $value) {
                $room->services()->attach($value['service_id'], [
                    'description' => $value['description'],
                    'price' => $value['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            //save room bed types
            foreach ($request->room_bed_types as $key => $value) {
                $room->bed_types()->attach($value['id'], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            //save other images
            if ($request->file('room_other_images') && $request->file('room_other_images')[0]['img'] != null) {
                $arr = [];
                foreach ($request->file('room_other_images') as $key => $value) {
                    // dd($value['img']);
                    # code...
                    $file2 = $value['img'];
                    $filename2 = date('YmdHi').$file2->getClientOriginalName().$key;
                    $file2-> move(storage_path('app/public/rooms/other'), $filename2);

                    $other_image = new RoomOtherImage([
                        'image' => $filename2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    array_push($arr, $other_image);
                }
                // dd($arr);
                $room->other_images()->saveMany($arr);
            }

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new RoomResource($room)
                ],
                'message' => 'Room created successfully'
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
    public function show(Room $room)
    {
        return new RoomResource(Room::with('bed_types','services','other_images','room_type')->find($room->id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        try {
            DB::beginTransaction();

            if ($request->file('main_image')) {
                $file= $request->file('main_image');
                $filename= date('YmdHi').$file->getClientOriginalName();
                $file-> move(storage_path('app/public/rooms'), $filename);
            } else {
                $filename = $room->main_image;
            }

            $services = [];
            $total_services_amt = 0;

            //push exist services
            foreach ($request->current_room_services as $key => $value) {
                array_push($services, [
                    'service_id' => $value['id'],
                    'description' => $value['description'],
                    'price' => isset($value['price_decimal']) ? $value['price_decimal'] : null,
                ]);
                if (isset($value['price_decimal'])) {
                    # code...
                    $total_services_amt += (float)$value['price_decimal'];
                }
            }

            //push services
            foreach ($request->room_services as $key => $value) {
                if (isset($value['service'])) {
                    # code...
                    array_push($services, [
                        'service_id' => $value['service'],
                        'description' => $value['description'],
                        'price' => $value['price'],
                    ]);
                    $total_services_amt += (float)$value['price'];
                }
            }

            $room->update([
                'name' => $request->validated()['name'],
                'room_type_id' => $request->validated()['room_type_id'],
                'description' => $request->validated()['description'],
                'number_of_guests' => $request->validated()['number_of_guests'],
                'total_count' => $request->validated()['counter'],
                'room_size' => $request->validated()['room_size'],
                'breakfirst_included' => $request->validated()['breakfirst_included'],
                'main_image' => $filename,
                'price_amt' => $request->validated()['price_amt'],
                'net_services_amt' => $total_services_amt,
                'total_amt' => (float)$request->validated()['price_amt'] + $total_services_amt,
                'status' => $request->validated()['status'],
            ]);

            $room->services()->detach();

            //save room services
            foreach ($services as $key => $value) {
                $room->services()->attach($value['service_id'], [
                    'description' => $value['description'],
                    'price' => $value['price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $room->bed_types()->detach();

            //save room bed types
            foreach ($request->room_bed_types as $key => $value) {
                $room->bed_types()->attach($value['id'], [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            //save other images
            if ($request->file('room_other_images') && $request->file('room_other_images')[0]['img'] != null) {
                $arr = [];
                foreach ($request->file('room_other_images') as $key => $value) {
                    // dd($value['img']);
                    # code...
                    $file2 = $value['img'];
                    $filename2 = date('YmdHi').$file2->getClientOriginalName().$key;
                    $file2-> move(storage_path('app/public/rooms/other'), $filename2);

                    $other_image = new RoomOtherImage([
                        'image' => $filename2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    array_push($arr, $other_image);
                }
                // dd($arr);
                $room->other_images()->saveMany($arr);
            }

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new RoomResource($room)
                ],
                'message' => 'Room updated successfully'
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        try {
            DB::beginTransaction();

            $room->update(['status' => $room->status ? 0 : 1]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new RoomResource($room)
                ],
                'message' => $room->status ? 'Requested room activated successfully' :'Requested room disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function get_services($id)
    {
        $room_services = Room::find($id)->services;
        return ServiceResource::collection(Service::whereNotIn('id', $room_services->pluck('id'))->get());
    }

    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_service($id)
    {
        try {
            DB::beginTransaction();

            $room_service = RoomService::find($id);
            $room_service->delete();

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [],
                'message' => 'requested room service deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_other_images($id)
    {
        try {
            DB::beginTransaction();

            $room_other = RoomOtherImage::find($id);
            $room_other->delete();

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [],
                'message' => 'requested room other image deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    
}
