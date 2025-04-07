<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Получить данные заказа по номеру заказа
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrderByNumber(Request $request): JsonResponse
    {
        try {
            // Проверяем, пришел ли запрос с параметром маршрута или из тела запроса
            if ($request->route('orderNumber')) {
                // Если запрос пришел через маршрут /order/{orderNumber}
                $orderNumber = $request->route('orderNumber');
            } else {
                // Валидация входных данных из тела запроса
                $validated = $request->validate([
                    'order_number' => 'required|string',
                ]);
                $orderNumber = $validated['order_number'];
            }

            // Поиск заказа по номеру
            $order = Order::where('order_number', $orderNumber)->first();

            // Проверка наличия заказа
            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Заказ не найден',
                ], 404);
            }

            // Возвращаем данные заказа
            return response()->json([
                'success' => true,
                'data' => $order,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при обработке запроса',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}