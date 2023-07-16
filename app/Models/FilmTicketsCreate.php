<?php

namespace App\Models;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Класс создания/удаления таблицы с перечнем купленных/проданных билетов
 */
class FilmTicketsCreate
{    	
    public function up(string $name): void
    {
        
        Schema::create($name, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('film_id')->nullable();
            $table->foreign('film_id')
                ->references('id')->on('films')->onDelete(('cascade'));
            
            $table->integer('row') ->nullable(false);              // ряд в зале, к которому принадлежит место
            $table->integer('number') ->nullable(false);           // номер места в ряду
            $table->integer('type') ->default(0);                  // тип места: 1-обычное; 2-VIP
            $table->string('qr-code') ->default(0);                // QR-код проданного билета
            $table->boolean('sold') ->default(false);              // маркер "билет продан"          
        });
    }
    
    public function down($name): void
    {
        Schema::dropIfExists($name);
    }
};
