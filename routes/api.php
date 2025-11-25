<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ServicesController;
use App\Http\Controllers\Api\CostEstimationController;
use App\Http\Controllers\Api\SubCategoryItemController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/otp-login', [AuthController::class, 'sendOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);


Route::middleware('auth:api')->group(function () {
    Route::post('/service-location', [AuthController::class, 'serviceLocation']);
    Route::get('/profile', [ProfileController::class, 'profile']);
    Route::post('/profile/update-image', [ProfileController::class, 'updateImage']);
    Route::post('/user/address', [UserController::class, 'store']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/sub-category-items', [SubCategoryItemController::class, 'index']);
    Route::get('/services', [ServicesController::class, 'index']);
    Route::post('/cart/add', [CartController::class, 'addItem']);
    Route::post('/cart/increase', [CartController::class, 'increaseItem']);
    Route::post('/cart/decrease', [CartController::class, 'decreaseItem']);
    Route::get('/cart/view', [CartController::class, 'viewCart']);
    Route::post('/service-slot', [BookingController::class, 'serviceSlot']);
    Route::get('/provider/new-jobs', [BookingController::class, 'providerBookings']);
    Route::post('/provider/bookings/{id}/status', [BookingController::class, 'changeBookingStatus']);
    Route::get('/provider/job-list', [BookingController::class, 'providerJobs']);
    Route::post('/provider/rate-user', [ReviewController::class, 'rateUser']);

    Route::get('/provider/service-parts', [CostEstimationController::class, 'getServiceParts']);
    Route::post('/provider/cost-estimations', [CostEstimationController::class, 'costEstimations']);

    Route::post('/cost-estimations/update-status', [CostEstimationController::class, 'updateStatus']);
    Route::post('/user/rate-provider', [ReviewController::class, 'rateProvider']);

    Route::get('/wallet/balance', [WalletController::class, 'walletBalance']);
    Route::get('/wallet/transactions', [WalletController::class, 'walletTransactions']);
    Route::post('/wallet/add', [WalletController::class, 'addMoney']);
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw']);

    Route::get('/conversations', [MessageController::class, 'getConversations']);
    Route::get('/conversations/{bookingId}', [MessageController::class, 'getMessages']);
    Route::post('/conversations/send', [MessageController::class, 'sendMessage']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
