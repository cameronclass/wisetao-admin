<?php

namespace App\Filament\Resources\ApiTokenResource\Pages;

use App\Filament\Resources\ApiTokenResource;
use App\Models\ApiToken;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateApiToken extends CreateRecord
{
    protected static string $resource = ApiTokenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Генерируем случайный токен при создании
        $data['token'] = Str::random(64);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
