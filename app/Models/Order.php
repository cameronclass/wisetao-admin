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
        'receipt_date',
        'marking',
        'customer_order_number',
        'delivery_type',
        'departure_place',
        'customer_code',
        'payment_method',
        'purpose',
        'name',
        'cargo_type',
        'place',
        'weight',
        'volume',
        'density',
        'cargo_cost',
        'insurance',
        'rate',
        'delivery_cost',
        'packaging_cost',
        'loading_unloading_cost',
        'total_invoice_amount',
        'cod',
        'recipient',
        'phone',
        'brand_name',
        'status',
        'recipient_address',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'receipt_date' => 'date',
        'weight' => 'decimal:2',
        'volume' => 'decimal:2',
        'density' => 'decimal:2',
        'cargo_cost' => 'decimal:2',
        'insurance' => 'decimal:2',
        'rate' => 'decimal:2',
        'delivery_cost' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
        'loading_unloading_cost' => 'decimal:2',
        'total_invoice_amount' => 'decimal:2',
        'cod' => 'decimal:2',
    ];
}
