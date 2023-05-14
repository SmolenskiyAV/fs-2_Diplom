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
        Schema::create('filmsessions', function (Blueprint $table) {    // ТАБЛИЦА "СЕАНСЫ ФИЛЬМОВ"
            $table->id();
            $table->string('film_name')->nullable(false);
            $table->date('film_date')->nullable(false);
            $table->time('film_start')->nullable(false);
            $table->time('film_stop')->nullable(false);
            $table->string('hall_name')->nullable(false);
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
        Schema::dropIfExists('film_sessions');
    }
};
