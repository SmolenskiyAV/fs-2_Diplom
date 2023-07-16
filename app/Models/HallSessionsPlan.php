<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * МОДЕЛЬ таблицы "План сеансов на день" (для каждого зала на каждый день отдельная таблица)
 */
class HallSessionsPlan extends Model
{
    use HasFactory;

    private string $name;
    public $timestamps = true;
    protected $fillable = ['film_name', 'film_tickets', 'film_start', 'film_stop', 'admin_updater'];
			
	public static function relation($sessions_date, $hall_name)
	{
		$name = $hall_name . '*' . $sessions_date;

        HallSessionsPlan::tableId($name);    // привязка модели к динамически создаваемой таблице c мидлфиксом "*" в имени

        return (new HallSessionsPlan());
        
	}

    /**
     * Метод переприсваивания protected $table (нужен для привязки модели к динамически создаваемой таблице)
     *
     */
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

    public function hall()  
    {
        return $this->belongsTo(Hall::class)->withDefault();   // обратное отношение "Многие-ко-Одному" (План_сеансов_на_день -> Зал)
    }

}
