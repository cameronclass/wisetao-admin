<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiTokenController extends Controller
{
    /**
     * Получить список всех API-токенов.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $tokens = ApiToken::all(['id', 'name', 'description', 'last_used_at', 'expires_at', 'created_at']);

        return response()->json([
            'data' => $tokens,
        ]);
    }

    /**
     * Создать новый API-токен.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors(),
            ], 422);
        }

        $token = ApiToken::createToken(
            $request->name,
            $request->description,
            $request->expires_at ? now()->parse($request->expires_at) : null
        );

        return response()->json([
            'message' => 'API-токен успешно создан',
            'data' => [
                'id' => $token->id,
                'name' => $token->name,
                'token' => $token->token, // Показываем токен только при создании
                'description' => $token->description,
                'expires_at' => $token->expires_at,
                'created_at' => $token->created_at,
            ],
        ], 201);
    }

    /**
     * Получить информацию о конкретном API-токене.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $token = ApiToken::findOrFail($id);

        return response()->json([
            'data' => [
                'id' => $token->id,
                'name' => $token->name,
                'description' => $token->description,
                'last_used_at' => $token->last_used_at,
                'expires_at' => $token->expires_at,
                'created_at' => $token->created_at,
            ],
        ]);
    }

    /**
     * Обновить информацию об API-токене.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $token = ApiToken::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors(),
            ], 422);
        }

        $token->update($request->only(['name', 'description', 'expires_at']));

        return response()->json([
            'message' => 'API-токен успешно обновлен',
            'data' => [
                'id' => $token->id,
                'name' => $token->name,
                'description' => $token->description,
                'last_used_at' => $token->last_used_at,
                'expires_at' => $token->expires_at,
                'created_at' => $token->created_at,
            ],
        ]);
    }

    /**
     * Удалить API-токен.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $token = ApiToken::findOrFail($id);
        $token->delete();

        return response()->json([
            'message' => 'API-токен успешно удален',
        ]);
    }

    /**
     * Обновить токен (создать новый).
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh($id)
    {
        $token = ApiToken::findOrFail($id);
        $token->token = Str::random(64);
        $token->save();

        return response()->json([
            'message' => 'API-токен успешно обновлен',
            'data' => [
                'id' => $token->id,
                'name' => $token->name,
                'token' => $token->token, // Показываем новый токен
                'description' => $token->description,
                'expires_at' => $token->expires_at,
                'created_at' => $token->created_at,
            ],
        ]);
    }
}
