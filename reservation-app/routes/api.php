<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\BedTypeController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\RegisterController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\VatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

    Route::post('login', [LoginController::class, 'login'])->name('login');

    Route::post('register', [RegisterController::class, 'register'])->name('register');

    Route::get('services/active', [ServiceController::class, 'active']);
    Route::get('bed_types/active', [BedTypeController::class, 'active']);
    Route::get('room_types/active', [RoomTypeController::class, 'active']);
    Route::get('users/active', [UserController::class, 'active']);
    Route::get('vats/active', [VatController::class, 'active']);
    Route::get('reservations/front', [ReservationController::class, 'front_save']);
    Route::get('rooms/active', [RoomController::class, 'active']);
    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('user', [AuthenticationController::class, 'user'])->name('user');
        Route::apiResource('services', ServiceController::class);
        Route::apiResource('bed_types', BedTypeController::class);
        Route::apiResource('room_types', RoomTypeController::class);
        Route::apiResource('vats', VatController::class);
        Route::apiResource('reservations', ReservationController::class);
        Route::apiResource('users', UserController::class);
        Route::delete('rooms/service/{id}', [RoomController::class, 'destroy_service']);
        Route::delete('rooms/other_images/{id}', [RoomController::class, 'destroy_other_images']);
        Route::get('rooms/{id}/available_services', [RoomController::class, 'get_services']);
        Route::apiResource('rooms', RoomController::class);
        Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    });

