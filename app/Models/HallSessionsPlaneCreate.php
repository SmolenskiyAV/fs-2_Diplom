<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Класс создания/удаления таблицы "План сеансов в зале на конкретный день"
 */
class HallSessionsPlaneCreate
{    	
    public function up(string $name): void
    {
        
        Schema::create($name, function (Blueprint $table) { // ТАБЛИЦА "СЕАНСЫ ФИЛЬМОВ НА ДЕНЬ"
            $table->id();
            $table->unsignedBigInteger('hall_id')->nullable();
            $table->foreign('hall_id')
                ->references('id')->on('halls');
            
            $table->string('film_name')->nullable(false);
            $table->string('film_tickets')->nullable(false);  // имя таблицы с перечнем купленных/проданных билетов на данный сеанс этого зала
            $table->time('film_start')->nullable(false);
            $table->integer('film_duration')->nullable(false);
            $table->string('admin_updater', 100) ->nullable();
            $table->timestamps();        
        });
    }

    public function down($name): void
    {
        Schema::dropIfExists($name);
    }
};
