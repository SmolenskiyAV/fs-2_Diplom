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
        Schema::create('films', function (Blueprint $table) {   // ТАБЛИЦА "ФИЛЬМЫ"
            $table->id();
            $table->string('film_name', 60)->nullable(false)->unique('film_name');    // имя фильма 
            $table->integer('film_duration')->nullable(false);
            $table->string('poster_path');                                            // путь к постеру фильма на сервере
            $table->integer('session_films') ->default(0);             // количество действующих сеансов для данного фильма
            $table->string('description') ->nullable();
            $table->string('country_source', 100) ->nullable();
            $table->string('admin_updater', 100) ->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('films');
    }
};
