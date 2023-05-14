<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


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

    public function sessions()
    {
        return $this->belongsToMany(FilmSession::class);    // отношение "Многие-ко-Многим" (Залы -> Сеансы_фильмов)
    }

    public function films()
    {
        return $this->belongsToMany(Film::class);   // отношение "Многие-ко-Многим" (Залы -> Фильмы)
    }
}
