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
            'departure_date' => $this->departure_date->format('d.m.Y'),
            'cargo_location' => $this->cargo_location,
            'delivery_stage' => $this->delivery_stage,
            'delivery_status' => [
                'current_stage' => $this->delivery_stage,
                'stages' => [
                    [
                        'stage' => 1,
                        'name' => 'В пути по Китаю',
                        'date' => $this->departure_date->addDays(5)->format('d.m.Y')
                    ],
                    [
                        'stage' => 2,
                        'name' => 'Проходит таможенный контроль',
                        'date' => $this->departure_date->addDays(10)->format('d.m.Y')
                    ],
                    [
                        'stage' => 3,
                        'name' => 'В пути по России',
                        'date' => $this->departure_date->addDays(15)->format('d.m.Y')
                    ],
                    [
                        'stage' => 4,
                        'name' => 'Доставлено в место назначения',
                        'date' => $this->departure_date->addDays(25)->format('d.m.Y')
                    ]
                ],
                'location' => $this->cargo_location
            ],
        ];

        return array_filter($data, function ($value) {
            return $value !== null;
        });}
    }
}