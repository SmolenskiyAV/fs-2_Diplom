<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilmTickets extends Model // МОДЕЛЬ таблицы "Билеты на сеанс" (для каждого сеанса отдельная таблица)
{
    use HasFactory;

    private string $name;
    public $timestamps = false;

			
	public static function relation($film_session_name, $film_session_time)
	{
		$name = $film_session_name . $film_session_time . '_tickets';     // $film_session_name представляет собой конкатинацию из HallSessionsPlan::relation ($hall_name . '*' . $sessions_date)
                                                                          // $film_session_time это время начала фильма в данный день в данном зале
        HallSeatsPlan::tableId($name);    // привязка модели к динамически создаваемой таблице c постфиксом "_tickets" в имени

        return (new HallSeatsPlan());
        
	}

    // *** метод переприсваивания protected $table ***
    protected $table = '';

    static $tableId = null;

    public function getTable()
    {
        return $this->table . static::$tableId;
    }

    public static function tableId($tableId = null)
    {
        if (is_null($tableId)) {
            return static::$tableId;
        }

        static::$tableId = $tableId;
    }
    // ***********************************************

    protected $fillable = ['row', 'number', 'type', 'qr-code', 'sold'];

    public function film()  
    {
        return $this->belongsTo(Film::class)->withDefault();   // обратное отношение "Многие-ко-Одному" (Билеты_на_сеанс -> Фильм)
    }

}
