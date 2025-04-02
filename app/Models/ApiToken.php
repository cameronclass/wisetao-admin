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
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'token',
        'description',
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
     * Generate a new API token.
     *
     * @param string $name
     * @param string|null $description
     * @param \DateTimeInterface|null $expiresAt
     * @return static
     */
    public static function createToken(string $name, ?string $description = null, ?\DateTimeInterface $expiresAt = null): self
    {
        return static::create([
            'name' => $name,
            'token' => Str::random(64),
            'description' => $description,
            'expires_at' => $expiresAt,
        ]);
    }

    /**
     * Check if the token is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return $this->expires_at !== null && now()->gt($this->expires_at);
    }

    /**
     * Update the last used timestamp.
     *
     * @return bool
     */
    public function markAsUsed(): bool
    {
        return $this->update([
            'last_used_at' => now(),
        ]);
    }
}
