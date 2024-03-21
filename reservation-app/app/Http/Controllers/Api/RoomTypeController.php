<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomType\StoreRoomTypeRequest;
use App\Http\Requests\RoomType\UpdateRoomTypeRequest;
use App\Http\Resources\RoomTypeResource;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RoomTypeResource::collection(RoomType::paginate(10));
    }

                /**
     * Display a listing of the resource.
     */
    public function active()
    {
        return RoomTypeResource::collection(RoomType::where('status', 1)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoomTypeRequest $request)
    {
        try {
            DB::beginTransaction();

            $room_type = RoomType::create($request->validated());

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new RoomTypeResource($room_type)
                ],
                'message' => 'Room Type created successfully'
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
    public function show(RoomType $roomType)
    {
        return new RoomTypeResource($roomType);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomTypeRequest $request, RoomType $roomType)
    {
        try {
            DB::beginTransaction();

            $roomType->update($request->validated());

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new RoomTypeResource($roomType)
                ],
                'message' => 'Requested room type updated successfully'
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
    public function destroy(RoomType $roomType)
    {
        try {
            DB::beginTransaction();

            $roomType->update(['status' => $roomType->status ? 0 : 1]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new RoomTypeResource($roomType)
                ],
                'message' => $roomType->status ? 'Requested room type activated successfully' :'Requested room type disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
