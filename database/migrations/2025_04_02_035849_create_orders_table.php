<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->string('recipient');
            $table->text('recipient_address');
            $table->decimal('volume', 10, 2)->comment('в м3');
            $table->decimal('weight', 10, 2)->comment('в kg');
            $table->enum('payment_status', ['Оплачено', 'Не оплачено'])->default('Не оплачено');
            $table->enum('delivery_method', ['Авто', 'Авиа', 'Жд']);
            $table->date('departure_date');
            $table->enum('cargo_location', ['По Китаю', 'Таможня', 'В пути по россии', 'Прибыл на место назначение'])->default('По Китаю');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
