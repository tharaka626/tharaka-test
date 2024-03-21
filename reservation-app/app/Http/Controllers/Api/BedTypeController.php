<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BedType\StoreBedTypeRequest;
use App\Http\Requests\BedType\UpdateBedTypeRequest;
use App\Http\Resources\BedTypeResource;
use App\Models\BedType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BedTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return BedTypeResource::collection(BedType::paginate(10));
    }

            /**
     * Display a listing of the resource.
     */
    public function active()
    {
        return BedTypeResource::collection(BedType::where('status', 1)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBedTypeRequest $request)
    {
        try {
            DB::beginTransaction();

            $bed_type = BedType::create($request->validated());

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new BedTypeResource($bed_type)
                ],
                'message' => 'Bed Type created successfully'
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
    public function show(BedType $bedType)
    {
        return new BedTypeResource($bedType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBedTypeRequest $request, BedType $bedType)
    {
        try {
            DB::beginTransaction();

            $bedType->update($request->validated());

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new BedTypeResource($bedType)
                ],
                'message' => 'Requested bed type updated successfully'
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
    public function destroy(BedType $bedType)
    {
        try {
            DB::beginTransaction();

            $bedType->update(['status' => $bedType->status ? 0 : 1]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new BedTypeResource($bedType)
                ],
                'message' => $bedType->status ? 'Requested bed type activated successfully' :'Requested bed type disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
