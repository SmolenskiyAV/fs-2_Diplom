<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\Film;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;


class ClientController extends Controller
{
    /**
     * Массив имён всех таблиц, созданных в текущей БД
     *
     * @var array
     */
    public array $allTables;

    /**
     * список всех таблиц планов сеансов в БД
     *
     * @var array
     */
    public array $sessionsPlanTables;

    /**
     * список всех таблиц сеансов в БД 
     *
     * @var array
     */
    public array $sessionsDayPlanTables;
    public array $actualSessionsDays;
    public int $start_element;
    public int $current_size;
    public string $currentPlaneDate;
    public string $today;
    public int $start2_element;
    public array $actualFilms;
    public array $actualHalls;
    
    /**
     * в конструкторе то, что отрабатывается при каждой загрузке app_client-шаблона
     *
     * @param Request $request
     * 
     */
    public function __construct(Request $request)
    {        
        $this->allTables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
        $this->sessionsPlanTables = [];      
        $this->sessionsDayPlanTables = [];
        $this->actualSessionsDays = [];        
        
        $this->start_element = (int)$request->route()->parameter('start_element');
        $sessions_date = $request->route()->parameter('sessions_date');

        if (empty($sessions_date)) {
            $this->currentPlaneDate = '';
        } else $this->currentPlaneDate = $sessions_date; 

        if(!isset($this->start_element)) $this->start_element = 0;  
    
        foreach($this->allTables as $el) {

            if (preg_match("/(_tickets)/", $el->name)){
                $this->sessionsDayPlanTables[] = $el->name;
                continue;
            }
            if (preg_match("/.+(\*).+/", $el->name)){
                $this->sessionsPlanTables[] = $el->name;

                $temporal_array = explode("*" , $el->name);
                $planedHallDate = end($temporal_array);
                $temp_actualSessionsDays[] = $planedHallDate;
            }
        }
        
        if (isset($temp_actualSessionsDays)) {
            $this->actualSessionsDays = array_unique($temp_actualSessionsDays);   // убираем повторяющиеся даты    
        }          
               
        usort($this->actualSessionsDays, function($a, $b) { // сортируем массив дат по возрастанию
            return strtotime($a) - strtotime($b);
        });
        
        $actualSessionsDaysLenth = count($this->actualSessionsDays);
        if (($actualSessionsDaysLenth - (int)$this->start_element) > 4)
        {
            $this->current_size = 5; 
        } else {
            $this->current_size = $actualSessionsDaysLenth;
        }

        //============================================================================================

        date_default_timezone_set('Europe/Moscow');
        $today_obj = getdate();
        $today_month = $today_obj['mon'];
        $today_day = $today_obj['mday'];

        if ((int) $today_month < 10) $today_month = str_pad($today_month, 2, "0", STR_PAD_LEFT);    // если месяц = один разряд, дополняем его нулём
        if ((int) $today_day < 10) $today_day = str_pad($today_day, 2, "0", STR_PAD_LEFT);    // если день = один разряд, дополняем его нулём     
        $this->today = $today_obj['year'] . '-' . $today_month . '-' . $today_day;

        if(in_array($this->today, $this->actualSessionsDays)){  // если на сегодня сеанс/сы есть - отображаем
            $key = array_search($this->today, $this->actualSessionsDays);
            $this->start2_element = intdiv($key, 5);             
        } else {                                // если сеансов на сегодня нет, переходим в начало списка дат
            if (isset($this->actualSessionsDays[0])) $this->today = $this->actualSessionsDays[0];
            $this->start2_element = 0;
        }
        
        //============================================================================================
        
        foreach($this->sessionsDayPlanTables as $el) {
            if(str_contains($el, $this->currentPlaneDate)) {

                $temp_seat = DB::table($el)->get();
                $acualFilm = $temp_seat[0]->film_id;
                $this->actualFilms[] = DB::table('films')->where('id', $acualFilm)->value('film_name');   // список всех экспонируемых в данный день фильмов
                
                $temporal_array1 = explode("*" , $el);                    
                $this->actualHalls[] = current($temporal_array1);    // список всех работающих в данный день залов
            }
        }

        if (isset($this->actualFilms)){
            $this->actualFilms = array_unique($this->actualFilms);   // убираем повторяющиеся фильмы
        }
        if (isset($this->actualHalls)){
            $this->actualHalls = array_unique($this->actualHalls);   // убираем повторяющиеся залы
        }
    }

    /**
     * ГЛАВНАЯ СТРАНИЦА БРОНИРОВАНИЯ БИЛЕТОВ
     */
    public function home()
    {          
        $hall_blocked = false;     // маркер
        return view('layouts.app_client', [
            'dataHalls' => Hall::paginate(), 
            'dataFilms' => Film::paginate(), 
            'hall_blocked' => $hall_blocked, 
            'allTables' => $this->allTables, 
            'sessionsPlanTables' => $this->sessionsPlanTables, 
            'sessionsDayPlanTables' => $this->sessionsDayPlanTables, 
            'actualSessionsDays' => $this->actualSessionsDays, 
            'start_element' => $this->start_element, 
            'current_size' => $this->current_size, 
            'currentPlaneDate' => $this->currentPlaneDate, 
            'today' => $this->today, 'start2_element' => $this->start2_element
        ]);
    }

    /**
     * ГЛАВНАЯ СТРАНИЦА БРОНИРОВАНИЯ БИЛЕТОВ (альтер маршрут)
     */
    public function client()
    {          
        $hall_blocked = false;     // маркер
        return view('layouts.app_client', [
            'dataHalls' => Hall::paginate(), 
            'dataFilms' => Film::paginate(), 
            'hall_blocked' => $hall_blocked, 
            'allTables' => $this->allTables, 
            'sessionsPlanTables' => $this->sessionsPlanTables, 
            'sessionsDayPlanTables' => $this->sessionsDayPlanTables, 
            'actualSessionsDays' => $this->actualSessionsDays, 
            'start_element' => $this->start_element, 
            'current_size' => $this->current_size , 
            'currentPlaneDate' => $this->currentPlaneDate, 
            'today' => $this->today, 
            'start2_element' => $this->start2_element
        ]);
    }

    /**
     * НАВИГАЦИЯ ПО КНОПКАМ ДАТЫ СЕАНСОВ
     *
     * @param mixed $sessions_date
     * @param mixed $start_element
     */
    public function btnDatePush($sessions_date, $start_element)
    {          
        $hall_blocked = false;     // маркер
        return view('layouts.app_client', [
            'dataHalls' => Hall::paginate(), 
            'sessions_date' => $sessions_date, 
            'dataFilms' => Film::paginate(), 
            'hall_blocked' => $hall_blocked, 
            'start_element' => $start_element, 
            'allTables' => $this->allTables, 
            'sessionsPlanTables' => $this->sessionsPlanTables, 
            'sessionsDayPlanTables' => $this->sessionsDayPlanTables, 
            'actualSessionsDays' => $this->actualSessionsDays, 
            'start_element' => $this->start_element, 
            'current_size' => $this->current_size, 
            'currentPlaneDate' => $this->currentPlaneDate, 
            'today' => $this->today, 
            'start2_element' => $this->start2_element
        ]);
    }    

    /**
     * НАВИГАЦИЯ ПО КНОПКАМ ВРЕМЯ СЕАНСОВ
     *
     * @param mixed $film_start
     * @param mixed $film_name
     * @param mixed $hall_name
     * @param mixed $film_date
     * @param mixed $tickets_table
     */
    public function btnTimePush($film_start, $film_name, $hall_name, $film_date, $tickets_table)
    {           
        return view('inc.app_hall', [
            'film_start' => $film_start, 
            'film_name' => $film_name, 
            'hall_name' => $hall_name, 
            'film_date' => $film_date, 
            'tickets_table' => $tickets_table]);
    }

    /**
     * ВЫБОР СЕАНСА (конкретные дата, время и зал)
     */
    public function halls()
    {           
        return view('inc.app_hall', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
    }

    /**
     * ВЫБОР МЕСТ В ЗАЛЕ
     *
     * @param Request $request
     */
    public function chooseTickets(Request $request)
    {           
        $film_name = $request->input('film_name');
        $hall_name = $request->input('hall_name');
        $film_date = $request->input('film_date');
        $session_time = $request->input('session_time');
        $total_cost = $request->input('total_cost');
        $arr = json_decode($request->input('arr'));
    
    return view('inc.app_payment', [
        'choose_array' => $arr, 
        'film_name' => $film_name, 
        'hall_name' => $hall_name, 
        'film_date' => $film_date, 
        'session_time' => $session_time, 
        'total_cost' => $total_cost]);
    }

    public function ticket() 
    {           
        return view('inc.app_ticket', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
    }

    /**
     * ОПЛАТА БИЛЕТОВ (БРОНИРОВАНИЕ)
     */
    public function payment()
    {           
        return view('inc.app_payment', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]);
    }

    /**
     * ГЕНЕРАЦИЯ КОДА БРОНИРОВАНИЯ БИЛЕТОВ
     *
     * @param Request $request
     */
    public function getTicketCode(Request $request)
    {
        $seats_list = $request->input('seats_list');
        $film_name = $request->input('film_name');
        $film_date = $request->input('film_date');
        $hall_name = $request->input('hall_name');
        $session_time = $request->input('session_time');
        
        $arr = json_decode($request->input('arr'));
        
        if (DB::table('halls')->where('hall_name', $hall_name)->value('active')) {
            $codeContents = $seats_list . $film_name . $hall_name . $session_time . time(); // стрОковое значение QR-кода
            $codeContents = preg_replace('/[[:punct:]]|[\s\s+]/', '', $codeContents);  //заменяем пробелы и спецсимволы из имени файла на "";
            $qrcodesDir = public_path() . '/storage/images/client/QRcodes/';    // место хранения на сервере png-файлов QR-кода

            $tempvalue = $qrcodesDir."$codeContents.png";             
            QrCode::format('png')->generate($codeContents, $tempvalue); // генерация png-файла QR-кода
                
            $qrimage = '/storage/images/client/QRcodes/' . "$codeContents.png"; // путь к image-файлу сгенерированного QR-кода
            $currentTableName = $hall_name . '*' . $film_date. $session_time . '_tickets';

            foreach($arr as $el) {
                $row = $el->row;
                $number = $el->seat;

                DB::table($currentTableName)->where('row', $row)->where('number', $number)->update([
                    'qr-code' => $codeContents,
                    'sold' => true
                ]); 
            }

            return view('inc.app_ticket', [
                'seats_list' => $seats_list, 
                'film_name' => $film_name, 
                'hall_name' => $hall_name, 
                'session_time' => $session_time, 
                'qrimage' => $qrimage]);
        } else {
            
            $hall_blocked = true;     // маркер, при наличии к-го выводится сообщ о закрытой продаже билетов

            return view('layouts.app_client', [
                'dataHalls' => Hall::paginate(), 
                'dataFilms' => Film::paginate(), 
                'hall' => $hall_name, 
                'hall_blocked' => $hall_blocked, 
                'allTables' => $this->allTables, 
                'sessionsPlanTables' => $this->sessionsPlanTables, 
                'sessionsDayPlanTables' => $this->sessionsDayPlanTables, 
                'actualSessionsDays' => $this->actualSessionsDays, 
                'start_element' => $this->start_element, 
                'current_size' => $this->current_size, 
                'currentPlaneDate' => $this->currentPlaneDate, 
                'today' => $this->today, 
                'start2_element' => $this->start2_element
            ]);
        }
    }
}