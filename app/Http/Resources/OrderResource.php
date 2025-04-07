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
        $data = [
            'order_number' => $this->order_number,
            'recipient' => $this->recipient,
            'recipient_address' => $this->recipient_address,
            'volume' => $this->volume,
            'weight' => $this->weight,
            'payment_status' => $this->payment_status,
            'delivery_method' => $this->delivery_method,
            'departure_date' => $this->departure_date,
            'cargo_location' => $this->cargo_location,
        ];

        return array_filter($data, function ($value) {
            return $value !== null;
        });}
    }
}