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
        Schema::create('rates', function (Blueprint $table) {
            $table->id(); // ID тарифа
            $table->unsignedBigInteger('period_id')->unique(); // Период за который выставляется счёт
            $table->float('amount_price'); // Цена за кубометр воды
            $table->timestampTz('create_date')->useCurrent(); // Дата создания тарифа

            $table->foreign('period_id')->references('id')->on('periods');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates');
    }
};
