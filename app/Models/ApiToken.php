<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'token',
        'description',
        'last_used_at',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Создать новый API токен
     *
     * @param string $name Название токена
     * @param string|null $description Описание токена
     * @param \DateTimeInterface|null $expiresAt Дата истечения срока действия
     * @return static
     */
    public static function createToken(string $name, ?string $description = null, ?\DateTimeInterface $expiresAt = null): self
    {
        return self::create([
            'name' => $name,
            'token' => Str::random(64),
            'description' => $description,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Проверить, действителен ли токен
     *
     * @return bool
     */
    public function isValid(): bool
    {
        if ($this->expires_at && now()->greaterThan($this->expires_at)) {
            return false;
        }

        return true;
    }

    /**
     * Обновить время последнего использования токена
     *
     * @return bool
     */
    public function markAsUsed(): bool
    {
        return $this->update([
            'last_used_at' => now(),
        ]);
    }

    /**
     * Найти токен по значению
     *
     * @param string $token
     * @return static|null
     */
    public static function findToken(string $token): ?self
    {
        return self::where('token', $token)->first();
    }
}
