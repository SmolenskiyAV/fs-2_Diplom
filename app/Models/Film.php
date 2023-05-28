<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model    // МОДЕЛЬ таблицы "Фильмы"
{
    use HasFactory;

    protected $table = 'films';

    public function halls()
    {
        return $this->belongsToMany(Hall::class);   // отношение "Многие-ко-Многим" (Фильмы -> Залы)
    }

    public function session()
    {
        return $this->hasMany(HallSessionsPlan::class);   // отношение "Один-ко-Многим" (Фильм -> Сеансы)
    }
    
}
