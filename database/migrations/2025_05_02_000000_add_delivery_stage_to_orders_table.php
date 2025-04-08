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
        Schema::table('orders', function (Blueprint $table) {
            $table->unsignedTinyInteger('delivery_stage')->default(1);
            $table->dropColumn('cargo_location');
            $table->enum('cargo_location', [
                'В пути по Китаю',
                'Проходит таможенный контроль',
                'В пути по России',
                'Доставлено в место назначения'
            ])->default('В пути по Китаю');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_stage');
            $table->dropColumn('cargo_location');
            $table->enum('cargo_location', [
                'По Китаю',
                'Таможня',
                'В пути по россии',
                'Прибыл на место назначение'
            ])->default('По Китаю');
        });
    }
};
