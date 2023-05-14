<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('session_hall', function (Blueprint $table) {    // сводная таблица "Сеансы <-> Залы" отношения "Многие-ко-Многим"
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('hall_id');
            $table->foreign('session_id')->references('id')->on('filmsessions');
            $table->foreign('hall_id')->references('id')->on('halls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_hall');
    }
};
