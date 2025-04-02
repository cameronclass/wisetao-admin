<?php

namespace Tests\Feature\Api;

use App\Models\ApiToken;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiTest extends TestCase
{
    use RefreshDatabase;

    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем API-токен для тестов
        $this->token = ApiToken::createToken('Test Token', 'Token for API testing');
    }

    /**
     * Тест получения списка заказов.
     */
    public function test_can_get_orders_list(): void
    {
        // Создаем несколько тестовых заказов
        Order::factory()->count(5)->create();

        // Делаем запрос к API с токеном
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token->token,
        ])->getJson('/api/orders');

        // Проверяем успешный ответ и структуру данных
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ]);
    }

    /**
     * Тест получения заказа по ID.
     */
    public function test_can_get_order_by_id(): void
    {
        // Создаем тестовый заказ
        $order = Order::factory()->create();

        // Делаем запрос к API с токеном
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token->token,
        ])->getJson('/api/orders/' . $order->id);

        // Проверяем успешный ответ и данные заказа
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);
    }

    /**
     * Тест получения заказа по номеру заказа.
     */
    public function test_can_get_order_by_order_number(): void
    {
        // Создаем тестовый заказ
        $order = Order::factory()->create();

        // Делаем запрос к API с токеном
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token->token,
        ])->getJson('/api/orders/by-number/' . $order->order_number);

        // Проверяем успешный ответ и данные заказа
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);
    }

    /**
     * Тест поиска заказов.
     */
    public function test_can_search_orders(): void
    {
        // Создаем тестовые заказы с разными статусами
        Order::factory()->count(3)->create(['status' => 'pending']);
        Order::factory()->count(2)->create(['status' => 'delivered']);

        // Делаем запрос к API с параметрами поиска
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token->token,
        ])->getJson('/api/orders/search?status=pending');

        // Проверяем успешный ответ и количество найденных заказов
        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 3,
                ],
            ]);
    }

    /**
     * Тест доступа к API без токена.
     */
    public function test_cannot_access_api_without_token(): void
    {
        // Делаем запрос к API без токена
        $response = $this->getJson('/api/orders');

        // Проверяем ответ с ошибкой аутентификации
        $response->assertStatus(401);
    }

    /**
     * Тест доступа к API с недействительным токеном.
     */
    public function test_cannot_access_api_with_invalid_token(): void
    {
        // Делаем запрос к API с недействительным токеном
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid_token',
        ])->getJson('/api/orders');

        // Проверяем ответ с ошибкой аутентификации
        $response->assertStatus(401);
    }
}
