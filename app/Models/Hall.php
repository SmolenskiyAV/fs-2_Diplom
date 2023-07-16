<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * МОДЕЛЬ таблицы "Залы"
 */
class Hall extends Model
{
    use HasFactory;

    protected $table = 'halls';
    
    public function hallSeatsPlan()
    {
        return $this->hasMany(HallSeatsPlan::class); // отношение "Один-ко-Многим" (Зал -> План_мест_в_зале)
    }

    public function hallBilling()
    {
        return $this->hasOne(HallBilling::class); // отношение "Один-ко-Одному" (Зал -> Цены_на_места_в_зале)
    }

    public function hallSessionsPlan()
    {
        return $this->hasMany(HallSessionsPlan::class);    // отношение "Один-ко-Многим" (Зал -> План_сеансов_на_день)
    }

}
