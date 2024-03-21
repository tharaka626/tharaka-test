<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vat\StoreVatRequest;
use App\Http\Requests\Vat\UpdateVatRequest;
use App\Http\Resources\VatResource;
use App\Models\Vat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return VatResource::collection(Vat::paginate(10));
        
    }
        /**
     * Display a listing of the resource.
     */
    public function active()
    {
        return new VatResource(Vat::where('end_date', '9999-12-31')->first());
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVatRequest $request)
    {
        try {
            DB::beginTransaction();

            Vat::where('end_date', '9999-12-31')->update(['end_date'=>date('Y-m-d')]);

            $vat = Vat::create([
                'precentage' => $request->validated()['precentage'],
                'start_date' => date('Y-m-d'),
                'end_date' => '9999-12-31',
            ]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new VatResource($vat)
                ],
                'message' => 'Vat created successfully'
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
    public function show(Vat $vat)
    {
        return new VatResource($vat);
        
    }


}
