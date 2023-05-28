<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallBilling extends Model // МОДЕЛЬ таблицы "Цены на места в зале"
{
    use HasFactory;

    protected $table = 'halls_billing';

    protected $fillable = ['usual_cost', 'vip_cost'];

    public function hall()
    {
        return $this->belongsTo(Hall::class);   // обратное отношение "Один-ко-Одному" (Цены_на_места_в_зале -> Зал)
    }
}
