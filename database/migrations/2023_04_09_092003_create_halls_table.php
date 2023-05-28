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
        Schema::create('halls', function (Blueprint $table) {   // ТАБЛИЦА "ЗАЛЫ"
            $table->id();
            $table->string('hall_name', 50)->nullable(false)->unique('hall_name');    // название зала
            $table->integer('rows') -> default(10);                      // количество рядов в зале
            $table->integer('seats_per_row') ->default(8);             // количество мест в ряду
            $table->integer('vip_seats') ->default(0);                      // количество VIP-мест
            $table->integer('usual_seats') ->default(64);               // количество обычных мест
            $table->integer('locked_seats') ->default(16);                   // количество заблокированных мест
            $table->integer('session_planes') ->default(0);             // количество действующих планов сеансов для данного зала
            $table->boolean('active')->default(false);                  // открыт/закрыт для продажи билетов 
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
        Schema::dropIfExists('halls');
    }
};
