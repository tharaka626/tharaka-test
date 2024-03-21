<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\DefaultResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DefaultResource::collection(User::paginate(10));
    }

        /**
     * Display a listing of the resource.
     */
    public function active()
    {
        return DefaultResource::collection(User::where('status', 1)->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->validated()['name'],
                'email' => $request->validated()['email'],
                'password' => $request->validated()['password'],
            ]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new DefaultResource($user)
                ],
                'message' => 'User created successfully'
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
    public function show(User $user)
    {
        return new DefaultResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'name' => $request->validated()['name'],
                'email' => $request->validated()['email'],
                'password' => isset($request->validated()['password']) ? bcrypt($request->validated()['password']) : $user->password,
            ]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new DefaultResource($user)
                ],
                'message' => 'Requested user updated successfully'
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
    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            $user->update(['status' => $user->status ? 0 : 1]);

            DB::commit();

            sleep(1);

            return response()->json([
                'data' => [
                    new DefaultResource($user)
                ],
                'message' => $user->status ? 'Requested user activated successfully' :'Requested user disabled successfully'
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
