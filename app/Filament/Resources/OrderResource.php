<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Отслеживания Заказа';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Информация о получателе')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Номер заказа')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('recipient')
                            ->label('Получатель')
                            ->required(),
                        Forms\Components\Textarea::make('recipient_address')
                            ->label('Адрес доставки')
                            ->required()
                            ->rows(3),
                    ])->columns(3),

                Forms\Components\Section::make('Информация о доставке')
                    ->schema([
                        Forms\Components\DatePicker::make('departure_date')
                            ->label('Дата отправления')
                            ->required(),
                        Forms\Components\Select::make('delivery_method')
                            ->label('Способ доставки')
                            ->options([
                                'Авто' => 'Авто',
                                'Авиа' => 'Авиа',
                                'Жд' => 'Жд',
                            ])
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->label('Статус оплаты')
                            ->options([
                                'Оплачено' => 'Оплачено',
                                'Не оплачено' => 'Не оплачено',
                            ])
                            ->required(),
                    ])->columns(3),

                Forms\Components\Section::make('Информация о грузе')
                    ->schema([
                        Forms\Components\TextInput::make('volume')
                            ->label('Объем')
                            ->numeric()
                            ->suffix('м³')
                            ->required(),
                        Forms\Components\TextInput::make('weight')
                            ->label('Вес')
                            ->numeric()
                            ->suffix('kg')
                            ->required(),
                        Forms\Components\TextInput::make('cargo_location')
                            ->label('Местоположение груза')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Обновляется автоматически'),
                        Forms\Components\TextInput::make('delivery_stage')
                            ->label('Этап доставки')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(4)
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('recipient')
                    ->label('Получатель')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo_location')
                    ->label('Местоположение груза')
                    ->description(fn(Order $record): string => $record->departure_date
                        ? $record->departure_date->format('d.m.Y')
                        : 'Дата отправки не указана')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_stage')
                    ->label('Этап доставки')
                    ->sortable(),
                Tables\Columns\TextColumn::make('recipient_address')
                    ->label('Адрес доставки')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('departure_date')
                    ->label('Дата отправления')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_method')
                    ->label('Способ доставки')
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->label('Статус оплаты')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Оплачено' => 'success',
                        'Не оплачено' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('volume')
                    ->label('Объем')
                    ->suffix(' м³')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Вес')
                    ->suffix(' kg')
                    ->sortable(),
                Tables\Columns\TextColumn::make('cargo_location')
                    ->label('Местоположение груза')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'По Китаю' => 'gray',
                        'Таможня' => 'warning',
                        'В пути по россии' => 'info',
                        'Прибыл на место назначение' => 'success',
                        default => 'primary',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('delivery_method')
                    ->label('Способ доставки')
                    ->options([
                        'Авто' => 'Авто',
                        'Авиа' => 'Авиа',
                        'Жд' => 'Жд',
                    ]),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->label('Статус оплаты')
                    ->options([
                        'Оплачено' => 'Оплачено',
                        'Не оплачено' => 'Не оплачено',
                    ]),
                Tables\Filters\SelectFilter::make('cargo_location')
                    ->label('Местоположение груза')
                    ->options([
                        'В пути по Китаю' => 'В пути по Китаю',
                        'Проходит таможенный контроль' => 'Проходит таможенный контроль',
                        'В пути по России' => 'В пути по России',
                        'Доставлено в место назначения' => 'Доставлено в место назначения',
                    ]),
                Tables\Filters\SelectFilter::make('delivery_stage')
                    ->label('Этап доставки')
                    ->options([
                        '1' => 'Этап 1',
                        '2' => 'Этап 2',
                        '3' => 'Этап 3',
                        '4' => 'Этап 4',
                    ]),
                Tables\Filters\Filter::make('departure_date')
                    ->form([
                        Forms\Components\DatePicker::make('departure_date_from')
                            ->label('С даты'),
                        Forms\Components\DatePicker::make('departure_date_until')
                            ->label('По дату'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['departure_date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('departure_date', '>=', $date),
                            )
                            ->when(
                                $data['departure_date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('departure_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Экспорт')
                        ->icon('heroicon-o-document-arrow-down')
                        ->action(function (\Illuminate\Database\Eloquent\Collection $records) {
                            // Здесь будет логика экспорта
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('payment_status', 'Не оплачено')->count() ?: null;
    }
}
