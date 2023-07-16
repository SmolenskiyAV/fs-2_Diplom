<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * МОДЕЛЬ таблицы "Схема зала" (план мест. для каждого зала отдельная таблица)
 */
class HallSeatsPlan extends Model
{
    use HasFactory;

    private string $name;
    public $timestamps = false;
    protected $table = '';
    static $tableId = null;
	protected $fillable = ['row', 'number', 'type'];

	/**
	 * Функция для создания новой таблицы "Схема зала" в БД (осздаётся не через миграции, а в зависимости от действий администратора)
	 *
	 * @param mixed $hall_name
	 * 
	 */
	public static function relation($hall_name)
	{
		$name = $hall_name . '_plane';

        HallSeatsPlan::tableId($name);    // привязка модели к динамически создаваемой таблице c постфиксом "_plane" в имени

        return (new HallSeatsPlan());
        
	}

    /**
     * Метод переприсваивания protected $table (нужен для привязки модели к динамически создаваемой таблице)
     *
     */
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
        return $this->belongsTo(Hall::class)->withDefault();   // обратное отношение "Многие-ко-Одному" (План_мест_в_зале -> Зал)
    }

}
