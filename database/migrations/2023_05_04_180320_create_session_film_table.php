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
        Schema::create('session_film', function (Blueprint $table) {    // сводная таблица "Сеансы <-> Фильмы" отношения "Многие-ко-Многим"
            $table->id();
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('film_id');
            $table->foreign('session_id')->references('id')->on('filmsessions');
            $table->foreign('film_id')->references('id')->on('films');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('session_film');
    }
};
