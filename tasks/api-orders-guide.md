# Руководство по использованию API для получения заказов

## Аутентификация API

Для доступа к API необходимо использовать API-токен. Токены можно создать и управлять ими в разделе "API Токены" административной панели.

### Использование токена

API-токен можно передать одним из следующих способов:

1. В заголовке `X-API-Token`:

```http
X-API-Token: ваш_токен
```

2. В заголовке `Authorization` с префиксом `Bearer`:

```http
Authorization: Bearer ваш_токен
```

### Управление токенами

В административной панели вы можете:

-   Создавать новые токены
-   Просматривать существующие токены
-   Обновлять токены
-   Устанавливать срок действия токенов
-   Удалять неиспользуемые токены

## Получение заказа по номеру

API поддерживает два способа получения информации о заказе по его номеру:

### 1. GET-запрос с параметром в URL

```http
GET /api/order/{orderNumber}
X-API-Token: ваш_токен
```

Пример:

```http
GET /api/order/123
X-API-Token: ваш_токен
```

### 2. GET-запрос с параметром в теле запроса

```http
GET /api/orders/get-by-number
Content-Type: application/json
X-API-Token: ваш_токен

{
    "order_number": "123"
}
```

### 3. POST-запрос с параметром в теле запроса

```http
POST /api/orders/get-by-number
Content-Type: application/json
X-API-Token: ваш_токен

{
    "order_number": "123"
}
```

## Формат ответа

Успешный ответ (200 OK):

```json
{
    "success": true,
    "data": {
        "id": 1,
        "order_number": "123",
        "receipt_date": "2023-12-20",
        "marking": "MARK-001",
        "customer_order_number": "CUS-001",
        "delivery_type": "standard",
        "departure_place": "Москва",
        "customer_code": "C12345",
        "payment_method": "card",
        "purpose": "delivery",
        "name": "Товар 1",
        "cargo_type": "box",
        "place": "склад 1",
        "weight": "10.50",
        "volume": "0.50",
        "density": "21.00",
        "cargo_cost": "5000.00",
        "insurance": "500.00",
        "rate": "1.00",
        "delivery_cost": "1000.00",
        "packaging_cost": "200.00",
        "loading_unloading_cost": "300.00",
        "total_invoice_amount": "7000.00",
        "cod": "0.00",
        "recipient": "Иван Иванов",
        "phone": "+7 (999) 123-45-67",
        "brand_name": "Brand X",
        "status": "processing",
        "recipient_address": "г. Москва, ул. Примерная, д. 1",
        "payment_status": "Не оплачено",
        "delivery_method": "Авто",
        "departure_date": "2023-12-25",
        "cargo_location": "По Китаю",
        "created_at": "2023-12-20T10:00:00.000000Z",
        "updated_at": "2023-12-20T10:00:00.000000Z"
    }
}
```

Ответ при отсутствии заказа (404 Not Found):

```json
{
    "success": false,
    "message": "Заказ не найден"
}
```

Ответ при ошибке валидации (422 Unprocessable Entity):

```json
{
    "success": false,
    "message": "Ошибка валидации",
    "errors": {
        "order_number": ["Поле order_number обязательно для заполнения."]
    }
}
```

## Рекомендации по использованию

1. Для получения заказа по номеру рекомендуется использовать GET-запрос с параметром в URL (`/api/order/{orderNumber}`), так как это наиболее RESTful подход.

2. Если вы уже используете POST-запрос к `/api/orders/get-by-number`, вы можете продолжать его использовать - API поддерживает оба метода для обратной совместимости.

3. При использовании Postman убедитесь, что:

    - Для GET-запроса с параметром в URL: параметр указан в URL
    - Для GET-запроса с телом: выбран метод GET и параметры указаны в теле запроса в формате JSON
    - Для POST-запроса: выбран метод POST и параметры указаны в теле запроса в формате JSON

4. Всегда проверяйте заголовок `Content-Type: application/json` при отправке запросов с телом в формате JSON.
