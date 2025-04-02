# Документация API для доступа к таблице заказов

## Общая информация

API предоставляет доступ к данным заказов через HTTP-запросы. Все запросы требуют аутентификации с использованием API-токена.

### Базовый URL

```
http://your-domain.com/api
```

### Аутентификация

Все запросы к API должны включать API-токен в заголовке `Authorization` в формате Bearer Token:

```
Authorization: Bearer your_api_token_here
```

Альтернативно, токен можно передать как параметр запроса `api_token`:

```
/api/orders?api_token=your_api_token_here
```

## Эндпоинты

### Получение списка заказов

```
GET /api/orders
```

#### Параметры запроса

| Параметр | Тип   | Описание                                         |
| -------- | ----- | ------------------------------------------------ |
| per_page | число | Количество записей на странице (по умолчанию 15) |
| page     | число | Номер страницы (по умолчанию 1)                  |

#### Пример запроса

```
GET /api/orders?per_page=10&page=1
Authorization: Bearer your_api_token_here
```

#### Пример ответа

```json
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-12345",
      "receipt_date": "2023-04-01",
      "status": "pending",
      "recipient": "Иван Иванов",
      "phone": "+7 (999) 123-45-67",
      ...
    },
    ...
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 42
  }
}
```

### Получение заказа по ID

```
GET /api/orders/{id}
```

#### Параметры пути

| Параметр | Тип   | Описание                |
| -------- | ----- | ----------------------- |
| id       | число | ID заказа в базе данных |

#### Пример запроса

```
GET /api/orders/1
Authorization: Bearer your_api_token_here
```

#### Пример ответа

```json
{
    "data": {
        "id": 1,
        "order_number": "ORD-12345",
        "receipt_date": "2023-04-01",
        "marking": "MARK-1234",
        "customer_order_number": "CUST-5678",
        "delivery_type": "express",
        "departure_place": "Москва",
        "customer_code": "CC-123",
        "payment_method": "card",
        "purpose": "Доставка товаров",
        "name": "Электроника",
        "cargo_type": "fragile",
        "place": 2,
        "weight": 5.75,
        "volume": 0.25,
        "density": 1.5,
        "cargo_cost": 15000.0,
        "insurance": 500.0,
        "rate": 25.5,
        "delivery_cost": 1200.0,
        "packaging_cost": 300.0,
        "loading_unloading_cost": 200.0,
        "total_invoice_amount": 17200.0,
        "cod": 0.0,
        "recipient": "Иван Иванов",
        "phone": "+7 (999) 123-45-67",
        "brand_name": "ООО Техника",
        "status": "pending",
        "recipient_address": "г. Санкт-Петербург, ул. Ленина, д. 10, кв. 5",
        "created_at": "2023-04-01T10:30:00.000000Z",
        "updated_at": "2023-04-01T10:30:00.000000Z"
    }
}
```

### Получение заказа по номеру заказа

```
GET /api/orders/by-number/{orderNumber}
```

#### Параметры пути

| Параметр    | Тип    | Описание                    |
| ----------- | ------ | --------------------------- |
| orderNumber | строка | Номер заказа (order_number) |

#### Пример запроса

```
GET /api/orders/by-number/ORD-12345
Authorization: Bearer your_api_token_here
```

#### Пример ответа

```json
{
  "data": {
    "id": 1,
    "order_number": "ORD-12345",
    ...
  }
}
```

### Поиск заказов

```
GET /api/orders/search
```

#### Параметры запроса

| Параметр              | Тип    | Описание                                  |
| --------------------- | ------ | ----------------------------------------- |
| order_number          | строка | Поиск по номеру заказа                    |
| customer_order_number | строка | Поиск по номеру заказа клиента            |
| status                | строка | Поиск по статусу заказа                   |
| recipient             | строка | Поиск по получателю                       |
| phone                 | строка | Поиск по телефону                         |
| from_date             | дата   | Поиск заказов с даты (формат YYYY-MM-DD)  |
| to_date               | дата   | Поиск заказов до даты (формат YYYY-MM-DD) |
| per_page              | число  | Количество записей на странице            |
| page                  | число  | Номер страницы                            |

#### Пример запроса

```
GET /api/orders/search?status=pending&from_date=2023-01-01&to_date=2023-04-01
Authorization: Bearer your_api_token_here
```

#### Пример ответа

```json
{
  "data": [
    {
      "id": 1,
      "order_number": "ORD-12345",
      "status": "pending",
      ...
    },
    ...
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 35
  }
}
```

## Коды ответов

| Код | Описание                                                 |
| --- | -------------------------------------------------------- |
| 200 | Успешный запрос                                          |
| 401 | Ошибка аутентификации (неверный или отсутствующий токен) |
| 404 | Ресурс не найден                                         |
| 422 | Ошибка валидации (неверные параметры запроса)            |
| 429 | Слишком много запросов (превышен лимит)                  |
| 500 | Внутренняя ошибка сервера                                |

## Примеры использования в Postman

1. Создайте новую коллекцию в Postman
2. Добавьте переменную окружения `api_token` с вашим API-токеном
3. Для каждого запроса добавьте заголовок:
    - Key: `Authorization`
    - Value: `Bearer {{api_token}}`
4. Создайте запросы для каждого эндпоинта, используя примеры выше

## Ограничения

-   Максимальное количество запросов: 60 в минуту
-   Максимальный размер ответа: 10 МБ
