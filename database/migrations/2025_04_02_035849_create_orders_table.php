<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->date('receipt_date');
            $table->string('marking')->nullable();
            $table->string('customer_order_number')->nullable();
            $table->string('delivery_type');
            $table->string('departure_place');
            $table->string('customer_code');
            $table->string('payment_method');
            $table->text('purpose')->nullable();
            $table->string('name');
            $table->string('cargo_type');
            $table->string('place')->nullable();
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->decimal('density', 10, 2)->nullable();
            $table->decimal('cargo_cost', 10, 2)->nullable()->comment('в $');
            $table->decimal('insurance', 10, 2)->nullable()->comment('в $');
            $table->decimal('rate', 10, 2)->nullable();
            $table->decimal('delivery_cost', 10, 2)->nullable()->comment('в $');
            $table->decimal('packaging_cost', 10, 2)->nullable()->comment('в ¥');
            $table->decimal('loading_unloading_cost', 10, 2)->nullable()->comment('в $');
            $table->decimal('total_invoice_amount', 10, 2)->nullable()->comment('в $');
            $table->decimal('cod', 10, 2)->nullable()->comment('сбор при доставке');
            $table->string('recipient');
            $table->string('phone');
            $table->string('brand_name')->nullable();
            $table->string('status');
            $table->text('recipient_address');
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