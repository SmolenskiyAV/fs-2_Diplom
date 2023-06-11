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
        Schema::create('halls_billing', function (Blueprint $table) {   // ТАБЛИЦА "ЦЕНЫ НА МЕСТА В ЗАЛЕ"
            $table->id();            
            $table->unsignedBigInteger('hall_id')->nullable();
            $table->foreign('hall_id')
                ->references('id')->on('halls')->onDelete('cascade');

            $table->string('hall_name') ->default(0);    // название зала
            $table->integer('usual_cost') ->default(0);  // цена за обычное место
            $table->integer('vip_cost') ->default(0);    // цена за VIP-место
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
        Schema::dropIfExists('halls_billing');
    }
};
