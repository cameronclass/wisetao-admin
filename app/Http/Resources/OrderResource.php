<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_number' => $this->order_number,
            'receipt_date' => $this->receipt_date,
            'marking' => $this->marking,
            'customer_order_number' => $this->customer_order_number,
            'delivery_type' => $this->delivery_type,
            'departure_place' => $this->departure_place,
            'customer_code' => $this->customer_code,
            'payment_method' => $this->payment_method,
            'purpose' => $this->purpose,
            'name' => $this->name,
            'cargo_type' => $this->cargo_type,
            'place' => $this->place,
            'weight' => $this->weight,
            'volume' => $this->volume,
            'density' => $this->density,
            'cargo_cost' => $this->cargo_cost,
            'insurance' => $this->insurance,
            'rate' => $this->rate,
            'delivery_cost' => $this->delivery_cost,
            'packaging_cost' => $this->packaging_cost,
            'loading_unloading_cost' => $this->loading_unloading_cost,
            'total_invoice_amount' => $this->total_invoice_amount,
            'cod' => $this->cod,
            'recipient' => $this->recipient,
            'phone' => $this->phone,
            'brand_name' => $this->brand_name,
            'status' => $this->status,
            'recipient_address' => $this->recipient_address,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
