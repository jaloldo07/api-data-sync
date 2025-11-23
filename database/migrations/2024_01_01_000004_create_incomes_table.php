<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->json('data'); // Все данные из API в формате JSON
            $table->date('income_date')->nullable(); // Дата дохода
            $table->string('external_id')->nullable()->index(); // ID из API
            $table->timestamps();
            
            // Уникальный индекс для предотвращения дубликатов
            $table->unique(['external_id', 'income_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('incomes');
    }
}
