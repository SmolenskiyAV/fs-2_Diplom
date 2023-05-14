<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmSession extends Model
{
    use HasFactory;

    protected $table = 'filmsessions';

    public function halls()
    {
        return $this->belongsToMany(Hall::class);   // отношение "Многие-ко-Многим" (Сеансы -> Залы)
    }

}
