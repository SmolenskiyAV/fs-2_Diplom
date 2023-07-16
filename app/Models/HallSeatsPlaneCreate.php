<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Класс создания/удаления таблицы "План мест в зале"
 */
class HallSeatsPlaneCreate
{
    	
    public function up(string $name): void
    {
        
        Schema::create($name, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hall_id')->nullable();
            $table->foreign('hall_id')
                ->references('id')->on('halls')->onDelete(('cascade'));
            
            $table->integer('row') ->nullable(false);              // ряд в зале, к которому принадлежит место
            $table->integer('number') ->nullable(false);           // номер места в ряду
            $table->integer('type') ->default(0);                  // тип места: 0-заблокировано; 1-обычное; 2-VIP            
        });
    }

    
    public function down($name): void
    {
        Schema::dropIfExists($name);
    }
};
