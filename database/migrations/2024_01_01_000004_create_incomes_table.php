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
            $table->json('data'); // API dan kelgan barcha ma'lumotlarni JSON formatda saqlaymiz
            $table->date('income_date')->nullable(); // Daromad sanasi
            $table->string('external_id')->nullable()->index(); // API dagi ID
            $table->timestamps();
            
            // Dublikat ma'lumotlarni oldini olish uchun
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
