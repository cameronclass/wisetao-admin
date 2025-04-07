<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

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

// Группа маршрутов, защищенных API-токеном
Route::middleware('verify.api.token')->group(function () {
    // Маршрут для получения данных заказа по номеру заказа
    Route::match(['get', 'post'], '/orders/get-by-number', [OrderController::class, 'getOrderByNumber']);

    // Альтернативный маршрут для получения заказа по номеру (для совместимости)
    Route::get('/order/{orderNumber}', [OrderController::class, 'getOrderByNumber']);
});
