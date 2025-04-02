<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ApiTokenController;

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

// Маршруты для управления API-токенами (доступны только аутентифицированным пользователям)
Route::middleware('auth:sanctum')->prefix('tokens')->group(function () {
    Route::get('/', [ApiTokenController::class, 'index']);
    Route::post('/', [ApiTokenController::class, 'store']);
    Route::get('/{id}', [ApiTokenController::class, 'show']);
    Route::put('/{id}', [ApiTokenController::class, 'update']);
    Route::delete('/{id}', [ApiTokenController::class, 'destroy']);
    Route::post('/{id}/refresh', [ApiTokenController::class, 'refresh']);
});

// Маршруты для доступа к заказам (требуют API-токен)
Route::middleware('verify.api.token')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);
    Route::get('/search', [OrderController::class, 'search']);
    Route::get('/by-number/{orderNumber}', [OrderController::class, 'getByOrderNumber']);
    Route::get('/{id}', [OrderController::class, 'show']);
});