<?php

namespace AppConsoleCommands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOrderLocations extends Command
{
    protected $signature = 'orders:update-locations';
    protected $description = 'Update order locations based on departure date';

    // Определяем статусы и время их смены (в днях)
    protected $locationStages = [
        ['days' => 0, 'status' => 'По китаю'],
        ['days' => 5, 'status' => 'таможня'],
        ['days' => 10, 'status' => 'в пути по россии'],
        ['days' => 20, 'status' => 'прибыл на место назначение']
    ];

    public function handle()
    {
        $orders = Order::whereNotNull('departure_date')
            ->where('cargo_location', '!=', 'доставлено')
            ->get();

        foreach ($orders as $order) {
            $daysFromDeparture = Carbon::parse($order->departure_date)->diffInDays(now());

            // Находим подходящий статус на основе прошедших дней
            $newStatus = null;
            foreach (array_reverse($this->locationStages) as $stage) {
                if ($daysFromDeparture >= $stage['days']) {
                    $newStatus = $stage['status'];
                    break;
                }
            }

            // Обновляем статус если он изменился
            if ($newStatus && $order->cargo_location !== $newStatus) {
                $order->update(['cargo_location' => $newStatus]);
                $this->info("Order {$order->order_number} location updated to: {$newStatus}");
            }
        }

        $this->info('Order locations update completed.');
    }
}