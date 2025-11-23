<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->json('data'); // Все данные из API в формате JSON
            $table->date('order_date')->nullable(); // Дата заказа
            $table->string('external_id')->nullable()->index(); // ID из API
            $table->timestamps();
            
            // Уникальный индекс для предотвращения дубликатов
            $table->unique(['external_id', 'order_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
