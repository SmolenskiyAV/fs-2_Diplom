<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HallSeatsPlan extends Model
{
    use HasFactory;

    private string $name;
    public $timestamps = false;

			
	public static function relation($hall_name)
	{
		$name = $hall_name . '_plane';

        HallSeatsPlan::tableId($name);    // привязка модели к динамически создаваемой таблице c постфиксом "_plane" в БД

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

    protected $fillable = ['row', 'number', 'type'];

    public function hall()  
    {
        return $this->belongsTo(Hall::class)->withDefault();   // обратное отношение "Многие-ко-Одному" (План_мест_в_зале -> Зал)
    }

}
