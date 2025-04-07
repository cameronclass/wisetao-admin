<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем наличие токена в заголовке X-API-Token
        $tokenValue = $request->header('X-API-Token');

        // Также проверяем токен в заголовке Authorization (Bearer token)
        if (!$tokenValue && $request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            if (str_starts_with($authHeader, 'Bearer ')) {
                $tokenValue = substr($authHeader, 7);
            }
        }

        // Если токен не найден, возвращаем ошибку
        if (!$tokenValue) {
            return response()->json([
                'success' => false,
                'message' => 'API токен не предоставлен',
            ], 401);
        }

        // Ищем токен в базе данных
        $token = ApiToken::findToken($tokenValue);

        // Если токен не найден или недействителен, возвращаем ошибку
        if (!$token || !$token->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Недействительный API токен',
            ], 401);
        }

        // Обновляем время последнего использования токена
        $token->markAsUsed();

        // Продолжаем выполнение запроса
        return $next($request);
    }
}
