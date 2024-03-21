<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\StoreServiceRequest;
use App\Http\Requests\Service\UpdateServiceRequest;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ServiceResource::collection(Service::paginate(10));
    }

    
        /**
     * Display a listing of the resource.
     */
    public function active()
    {
        return ServiceResource::collection(Service::where('status', 1)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = Service::create($request->validated());

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new ServiceResource($service)
                ],
                'message' => 'Service created successfully'
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
    public function show(Service $service)
    {
        return new ServiceResource($service);

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        try {
            DB::beginTransaction();

            $service->update($request->validated());

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new ServiceResource($service)
                ],
                'message' => 'Requested service updated successfully'
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
    public function destroy(Service $service)
    {
        try {
            DB::beginTransaction();

            $service->update(['status' => $service->status ? 0 : 1]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new ServiceResource($service)
                ],
                'message' => $service->status ? 'Requested service activated successfully' :'Requested service disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
