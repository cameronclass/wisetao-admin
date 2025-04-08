<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'order_number',
        'recipient',
        'recipient_address',
        'volume',
        'weight',
        'payment_status',
        'delivery_method',
        'departure_date',
        'cargo_location',
        'delivery_stage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'departure_date' => 'date',
        'weight' => 'decimal:2',
        'volume' => 'decimal:2',
        'delivery_stage' => 'integer',
    ];
}