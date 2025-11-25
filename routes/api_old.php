<?php

use App\Http\Controllers\Api\UserApiController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/request-otp', [UserApiController::class, 'requestOtp']);
Route::post('/verify-otp', [UserApiController::class, 'verifyOtp']);
Route::post('/register-customer', [UserApiController::class, 'registerCustomer']);


// Route::middleware(['auth:sanctum', 'customer'])->group(function () {
//     Route::get('/customer/dashboard', [UserApiController::class, 'dashboard']);
// });

Route::middleware(['auth:api'])->group(function () {
    Route::get('/customer/dashboard', [UserApiController::class, 'dashboard']);
    Route::post('/customer/logout', [UserApiController::class, 'logout']);
});
