<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallSessionsPlan extends Model    // МОДЕЛЬ таблицы "План сеансов на день" (для каждого зала на каждый день отдельная таблица)
{
    use HasFactory;

    private string $name;
    public $timestamps = true;

			
	public static function relation($sessions_date, $hall_name)
	{
		$name = $hall_name . '*' . $sessions_date;

        HallSessionsPlan::tableId($name);    // привязка модели к динамически создаваемой таблице c мидлфиксом "*" в имени

        return (new HallSessionsPlan());
        
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

    protected $fillable = ['film_name', 'film_tickets', 'film_start', 'film_stop', 'admin_updater'];

    public function hall()  
    {
        return $this->belongsTo(Hall::class)->withDefault();   // обратное отношение "Многие-ко-Одному" (План_сеансов_на_день -> Зал)
    }

}
