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

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationLabel = 'Заказы';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Основная информация')
                    ->schema([
                        Forms\Components\TextInput::make('order_number')
                            ->label('Номер заказа')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Forms\Components\DatePicker::make('receipt_date')
                            ->label('Дата квитанции')
                            ->required(),
                        Forms\Components\TextInput::make('marking')
                            ->label('Маркировка'),
                        Forms\Components\TextInput::make('customer_order_number')
                            ->label('Номер заказа клиента'),
                    ])->columns(2),

                Forms\Components\Section::make('Информация о доставке')
                    ->schema([
                        Forms\Components\TextInput::make('delivery_type')
                            ->label('Вид доставки')
                            ->required(),
                        Forms\Components\TextInput::make('departure_place')
                            ->label('Место отправки')
                            ->required(),
                        Forms\Components\TextInput::make('customer_code')
                            ->label('Код клиента')
                            ->required(),
                        Forms\Components\TextInput::make('payment_method')
                            ->label('Способ оплаты')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Информация о грузе')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Наименование')
                            ->required(),
                        Forms\Components\TextInput::make('cargo_type')
                            ->label('Вид груза')
                            ->required(),
                        Forms\Components\TextInput::make('place')
                            ->label('Место'),
                        Forms\Components\Textarea::make('purpose')
                            ->label('Назначения')
                            ->rows(2),
                        Forms\Components\TextInput::make('weight')
                            ->label('Вес')
                            ->numeric()
                            ->suffix('кг'),
                        Forms\Components\TextInput::make('volume')
                            ->label('Объем')
                            ->numeric()
                            ->suffix('м³'),
                        Forms\Components\TextInput::make('density')
                            ->label('Плотность')
                            ->numeric(),
                    ])->columns(2),

                Forms\Components\Section::make('Финансовая информация')
                    ->schema([
                        Forms\Components\TextInput::make('cargo_cost')
                            ->label('Стоимость груза')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('insurance')
                            ->label('Страховка')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('rate')
                            ->label('Ставка')
                            ->numeric(),
                        Forms\Components\TextInput::make('delivery_cost')
                            ->label('За доставку')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('packaging_cost')
                            ->label('Упаковка')
                            ->numeric()
                            ->prefix('¥'),
                        Forms\Components\TextInput::make('loading_unloading_cost')
                            ->label('Погрузочно-разгрузочные работы')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('total_invoice_amount')
                            ->label('Общая сумма накладной')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('cod')
                            ->label('Сбор при доставке (COD)')
                            ->numeric()
                            ->prefix('$'),
                    ])->columns(2),

                Forms\Components\Section::make('Информация о получателе')
                    ->schema([
                        Forms\Components\TextInput::make('recipient')
                            ->label('Получатель')
                            ->required(),
                        Forms\Components\TextInput::make('phone')
                            ->label('Телефон')
                            ->tel()
                            ->required(),
                        Forms\Components\Textarea::make('recipient_address')
                            ->label('Адрес получателя')
                            ->required()
                            ->rows(3),
                        Forms\Components\TextInput::make('brand_name')
                            ->label('Название бренда'),
                    ])->columns(2),

                Forms\Components\Section::make('Статус')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Статус заказа')
                            ->options([
                                'new' => 'Новый',
                                'processing' => 'В обработке',
                                'shipped' => 'Отправлен',
                                'delivered' => 'Доставлен',
                                'cancelled' => 'Отменен',
                            ])
                            ->required(),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('Номер заказа')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('receipt_date')
                    ->label('Дата квитанции')
                    ->date('d.m.Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('customer_order_number')
                    ->label('Номер заказа клиента')
                    ->searchable(),
                Tables\Columns\TextColumn::make('delivery_type')
                    ->label('Вид доставки')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Наименование')
                    ->searchable(),
                Tables\Columns\TextColumn::make('recipient')
                    ->label('Получатель')
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Телефон')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_invoice_amount')
                    ->label('Общая сумма')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'new' => 'info',
                        'processing' => 'warning',
                        'shipped' => 'primary',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'shipped' => 'Отправлен',
                        'delivered' => 'Доставлен',
                        'cancelled' => 'Отменен',
                        default => $state,
                    })
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Статус')
                    ->options([
                        'new' => 'Новый',
                        'processing' => 'В обработке',
                        'shipped' => 'Отправлен',
                        'delivered' => 'Доставлен',
                        'cancelled' => 'Отменен',
                    ]),
                Tables\Filters\Filter::make('receipt_date')
                    ->form([
                        Forms\Components\DatePicker::make('receipt_date_from')
                            ->label('С даты'),
                        Forms\Components\DatePicker::make('receipt_date_until')
                            ->label('По дату'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['receipt_date_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('receipt_date', '>=', $date),
                            )
                            ->when(
                                $data['receipt_date_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('receipt_date', '<=', $date),
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
        return static::getModel()::where('status', 'new')->count() ?: null;
    }
}