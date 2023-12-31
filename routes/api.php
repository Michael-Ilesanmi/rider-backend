<?php

use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\UserController;
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

Route::post('/register', [UserController::class, 'register'])->name('register');
Route::post('/login', [UserController::class, 'login'])->name('login');

Route::middleware(['auth:api'])->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::get('', [UserController::class, 'auth']);
        Route::get('rating', [OrderController::class, 'riderRating']);
        Route::group(['prefix' => 'order'], function () {
            Route::post('', [OrderController::class, 'placeOrder'])->name('place-order');
            Route::post('pickup', [OrderController::class, 'pickupOrder'])->name('pickup-order');
            Route::post('deliver', [OrderController::class, 'deliverOrder'])->name('deliver-order');
            Route::post('accept', [OrderController::class, 'acceptOrder'])->name('accept-order');
        });
        Route::group(['prefix' => 'orders'], function () {
            Route::get('pending', [OrderController::class, 'pendingOrders']);
            Route::get('history', [OrderController::class, 'orderHistory']);
        });
    });
});