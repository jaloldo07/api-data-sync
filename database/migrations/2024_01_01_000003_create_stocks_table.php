<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->json('data'); // Все данные из API в формате JSON
            $table->date('stock_date')->nullable(); // Дата склада (только текущий день)
            $table->string('external_id')->nullable()->index(); // ID из API
            $table->timestamps();
            
            // Уникальный индекс для предотвращения дубликатов
            $table->unique(['external_id', 'stock_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
