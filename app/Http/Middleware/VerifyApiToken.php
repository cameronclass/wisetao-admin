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
        $token = $request->bearerToken() ?? $request->query('api_token');

        if (!$token) {
            return response()->json([
                'message' => 'API токен не предоставлен',
            ], 401);
        }

        $apiToken = ApiToken::where('token', $token)->first();

        if (!$apiToken) {
            return response()->json([
                'message' => 'Недействительный API токен',
            ], 401);
        }

        if ($apiToken->isExpired()) {
            return response()->json([
                'message' => 'API токен истек',
            ], 401);
        }

        // Обновляем время последнего использования токена
        $apiToken->markAsUsed();

        // Добавляем токен к запросу для дальнейшего использования
        $request->attributes->set('api_token', $apiToken);

        return $next($request);
    }
}
