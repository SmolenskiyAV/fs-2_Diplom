<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Hall;
use Illuminate\Http\Request;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\CreateHallRequest;
use App\Http\Requests\CreateFilmRequest;
use App\Models\Film;
use App\Models\HallBilling;
use App\Models\HallSeatsPlan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\HallSeatsPlaneCreate;
use App\Models\HallSessionsPlan;
use App\Models\HallSessionsPlaneCreate;
use App\Models\FilmTicketsCreate;
use App\Models\FilmTickets;

use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;

class TodoController extends Controller
{
    public function index()
    {

    }

    public function add(CreateRequest $request)
    {
        //dd($request->input('task'));
        $task = new Todo();
        $task->name = $request->input('name');
        $task->task = $request->input('task');

        $task->save();

        return redirect()->route('home')->with('success', 'Новая задача успешно добавлена');
    }



    public function addHall(CreateHallRequest $request) // ДОБАВИТЬ ЗАЛ
    {
    //dd($request->input('hall_name'));
        $hall_name = $request->input('hall_name');
        $hall = new Hall();
        $hall->hall_name = $hall_name;
        
        $hall->save();                          // создание записи в таблице "зал"

        $plane = new HallSeatsPlaneCreate();
        $plane->up($hall_name . '_plane');      // создание зависимой таблицы "План мест в зале"

        $cost = new HallBilling();
        $cost->hall_name = $hall_name;

        $hall = Hall::where('hall_name', $hall_name)->first();
        $hall->hallBilling()->save($cost);      // создание записи в зависимой таблице "Цены на места в зале"

                          
        for ($r = 1; $r < 9; ++$r) {   // заполнение таблицы "План мест в зале" значениями по умолчанию

            for ($s = 1; $s < 11; ++$s){
            $plane = HallSeatsPlan::relation($hall_name);
            $plane->row = $r;
            $plane->number = $s;
            $plane->type = 1;

            $hall->hallSeatsPlan()->save($plane);      // создание дефолтной записи в зависимой таблице "План мест в зале"
            }
                  
        }    

        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'Новый зал успешно добавлен');
    }



    public function delHall(Request $request)   //  УДАЛИТЬ ЗАЛ
    {
        $hall_name = $request->input('hall_name');
                
        Schema::drop($hall_name . '_plane');            // удаление зависимой таблицы "План мест в зале"
        
        $allTables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");    // список всех таблиц БД
        
        foreach($allTables as $el) {    // сначала уменьшаем запись о количестве сеансов для данного фильма (связанного с удаляемым залом)

            if (str_contains($el->name, $hall_name)){                

                if (str_contains($el->name, '_tickets')) {
                    
                    $plane_name = mb_substr($el->name, 0, -13);                    
                    $film_name = DB::table($plane_name)->where('film_tickets', $el->name)->value('film_name');  
                    DB::table('films')->where('film_name', $film_name)->decrement('session_films', 1);    // 
                }              
            }
        }        

        foreach($allTables as $el) {    // удаление всех зависимых от данного зала таблиц
             /*
            if (preg_match("/.+(\*)[0-9,-]{10}$/", $el->name)){  //поиск всех удаляемых таблиц суточных сеансов <имязала*дата>
                DB::table('halls')->where('hall_name', $hall_name)->decrement('session_planes', 1);    // уменьшаем количество действующих планов сеансов для данного зала на 1
            }*/

            if (str_contains($el->name, $hall_name)){
                Schema::drop($el->name);               
            }            
        }

        Hall::where('hall_name', $hall_name)->delete(); // удаление записи в таблице "Зал"
                                            
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'Зал ' . $hall_name . ' успешно удалён');
    }



    public function sizeHall(Request $request)   //  ЗАДАТЬ РАЗМЕР ЗАЛА
    {
        $hall_name = $request->input('hall_cfg_size');
        $rows = $request->input('rows');
        $seats_per_row = $request->input('seats_per_row');
        $chair_standart_default = $rows * $seats_per_row;

        if (($chair_standart_default === 0) || ($rows > 40) || ($seats_per_row > 50)) {
            return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]) ->with('baddata', 'Новый размер зала ' . $hall_name . ' не может быть определён! Неверные параметры.');
        }

        DB::table('halls')->where('hall_name', $hall_name)->update([
            'seats_per_row' => $seats_per_row, 
            'rows' =>$rows,
            'vip_seats' =>0,
            'usual_seats' =>$chair_standart_default,
            'locked_seats' =>0
        ]);  
        
        $hall = Hall::where('hall_name', $hall_name)->first();
        DB::table($hall_name . '_plane')->truncate();             // очистка таблицы "План мест в зале" перед новым заполнением

        
        for ($r = 1; $r < $rows +1; ++$r) {   // заполнение таблицы "План мест в зале" новыми значениями по умолчанию

            for ($s = 1; $s < $seats_per_row +1; ++$s){
            $plane = HallSeatsPlan::relation($hall_name);
            $plane->row = $r;
            $plane->number = $s;
            $plane->type = 1;

            $hall->hallSeatsPlan()->save($plane);      // создание дефолтной записи в зависимой таблице "План мест в зале"
            }                  
        }        

        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'radioBtnPushed' => $hall_name, 'dataFilms' => Film::paginate()]) ->with('success', 'Размер зала ' . $hall_name . ' успешно изменён');
    }



    public function planeHall(Request $request)   //  ЗАДАТЬ ПЛАНИРОВКУ ЗАЛА
    {                        
        $hall_name = $request->input('hall_cfg_name');
        $hall_plane = json_decode(($request->input('hall_plane')), true);   // преобразование полученных json-данных в массив

        $hall = Hall::where('hall_name', $hall_name)->first();
        DB::table($hall_name . '_plane')->truncate();             // очистка таблицы "План мест в зале" перед новым заполнением

      
        $rows = 1;
        $seats_per_row =1;
        $vip_seats = 0;
        $usual_seats = 0;
        $locked_seats = 0;
                       
        for ($i = 0, $size = count($hall_plane); $i < $size; ++$i) {

            $plane = HallSeatsPlan::relation($hall_name);
            $plane->row = $hall_plane[$i][0];
            $plane->number = $hall_plane[$i][1];
            $plane->type = $hall_plane[$i][2];

            $hall->hallSeatsPlan()->save($plane);      // создание записи в зависимой таблице "План мест в зале"

            $rowVar = $hall_plane[$i][0];
            if($rowVar > $rows)  $rows = $rows +1;   // количество рядов в зале (для новой планировки)  
            if ($hall_plane[$i][2] == 0) $locked_seats++;   // количество заблокированных мест в зале (для новой планировки)
            if ($hall_plane[$i][2] == 1) $usual_seats++;    // количество стандартных мест в зале (для новой планировки)   
            if ($hall_plane[$i][2] == 2) $vip_seats++;      // количество vip-мест в зале (для новой планировки)
        }
        
        $seats_per_row = $size / $rows;     // количество мест в ряду (для новой планировки)

        DB::table('halls')->where('hall_name', $hall_name)->update([
            'seats_per_row' => $seats_per_row, 
            'rows' =>$rows,
            'vip_seats' =>$vip_seats,
            'usual_seats' =>$usual_seats,
            'locked_seats' =>$locked_seats
        ]);              
                                                      
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'radioBtnPushed' => $hall_name, 'dataFilms' => Film::paginate()])->with('success', 'Схема зала ' . $hall_name . ' успешно изменена');
    }



    public function billingHall(Request $request)   //  УСТАНОВИТЬ ЦЕНЫ ДЛЯ ТИПОВ КРЕСЕЛ
    {
        $hall_name = $request->input('hall_cfg_cost');
        $vip_cost = $request->input('hall_vip_cost');
        $usual_cost = $request->input('hall_usual_cost');

        DB::table('halls_billing')->where('hall_name', $hall_name)->update([
            'vip_cost' => $vip_cost, 
            'usual_cost' =>$usual_cost
        ]);              
                                                      
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'radioBtnPushed' => $hall_name, 'dataFilms' => Film::paginate()])->with('success', 'Цены на места в зале ' . $hall_name . ' успешно изменены');
    }

    public function btnPush($pushedBtn, $section)   // НАВИГАЦИЯ ПО РАДИО-КНОПКАМ
    {   // параметр $section определяет, куда будет перемещён скролл страницы после нажатия одной из радиокнопок
        return view('/layouts/app_admin', ['dataHalls' => Hall::paginate(), 'radioBtnPushed' => $pushedBtn, 'section' => $section, 'dataFilms' => Film::paginate()]);
    }

    public function addFilm(CreateFilmRequest $request) // ДОБАВИТЬ ФИЛЬМ
    {        
        $film_name = $request->input('film_name');
        $duration = $request->input('film_duration');        
        $extension = $request->poster->extension(); // получить расширение файла
        
        $poster_name = $film_name . '_poster';
        $poster_name = preg_replace('/[[:punct:]]|[\s\s+]/', '_', $poster_name);  //заменяем пробелы и спецсимволы из имени постера на "_";
        if($request->isMethod('post')) {

            if($request->hasFile('poster')) {
                $file = $request->file('poster');
                $file->move(public_path() . '/storage/images/films',"$poster_name.$extension");
            }
        }       

        $film = new Film();
        $film->film_duration = (int) $duration;
        $film->film_name = $film_name;
        $film->poster_path = '/storage/images/films/' . "$poster_name.$extension";
        
        $film->save();                          // создание записи в таблице "фильм"
        session()->flash('film_msg', true);     // маркер, определяющий, где на странице будут отображаться сессионные сообщения. Если 'true' - то в секции "Сетка сеансов"        
           
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'Новый фильм успешно добавлен');
    }



    public function delFilm(Request $request)   //  УДАЛИТЬ ФИЛЬМ
    {
        $film_name = $request->input('film_name');
               
        $poster_path = public_path() . DB::table('films')->where('film_name', $film_name)->value('poster_path');
        unlink($poster_path);

        Storage::disk('local')->delete($poster_path);                  // удаление постера, относящегося к данному фильму (не работает,сцуко..) :/
        Film::where('film_name', $film_name)->delete();                // удаление записи в таблице "Фильм"
        
        $allTables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");    // список всех таблиц БД
        
        foreach($allTables as $el) {

            if (preg_match("/.+(\*)[0-9,-]{10}$/", $el->name)){  //поиск всех таблиц суточных сеансов <имязала*дата>
               
                $target_sessions = DB::table($el->name)->get();
                                   
                foreach ($target_sessions as $table_tickets) {
                
                    $counter = DB::table($table_tickets->film_tickets)->count();
                    if($counter == 0) {
                            
                        Schema::drop($table_tickets->film_tickets);                                    // удаление зависимых от данного фильма таблиц "Билеты на сеанс" (относящихся к данному залу на данный день)
                        $deleted = DB::table($el->name)->where('film_name', $film_name)->delete();     // удаление записи в суточном плане сеансов, для запланированных фильмов с данным именем
                    
                        DB::table('films')->where('film_name', $film_name)->decrement('session_films', (int) $deleted);    // уменьшаем количество действующих сеансов фильма на количество всех удалённых сеансов
                    }              
                }                    
            }
        }        
        
        session()->flash('film_msg', true);     // маркер, определяющий, где на странице будут отображаться сессионные сообщения. Если 'true' - то в секции "Сетка сеансов"    
                                            
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'Фильм "' . $film_name . '" и его сеансы успешно удалены');
    }



    public function addSessionsPlan(Request $request) // ДОБАВИТЬ ПЛАН СЕАНСОВ НА КОНКРЕТНЫЙ ДЕНЬ
    {
        $hall_name = $request->input('hall_name');
        $sessions_date = $request->input('sessions_date');

        session()->flash('film_msg', true);     // маркер, определяющий, где на странице будут отображаться сессионные сообщения. Если 'true' - то в секции "Сетка сеансов"

        if (Schema::hasTable($hall_name . '*' . $sessions_date)) {

            return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()]) ->with('baddata', 'План сеансов зала ' . $hall_name . ' на ' . $sessions_date . ' уже существует!');

        } else {

            $sessions_plane = new HallSessionsPlaneCreate();
            $sessions_plane->up($hall_name . '*' . $sessions_date);      // создание зависимой таблицы "План сеансов на день"

            DB::table('halls')->where('hall_name', $hall_name)->increment('session_planes', 1);    // увеличиваем количество действующих планов сеансов для данного зала на 1 
        }
        
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'Новый план сеансов зала' . '"'. $hall_name .'"' . ' на ' . $sessions_date . ' успешно добавлен');
    }



    public function delSessionsPlan(Request $request) // УДАЛИТЬ ПЛАН СЕАНСОВ НА КОНКРЕТНЫЙ ДЕНЬ
    {
        $full_name = $request->input('fullPlanedName');
        $hall_name = $request->input('hallPlanedName');
        $sessions_date  = $request->input('hallPlanedDate');
        
        $allTables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");    // список всех таблиц БД
        
        foreach($allTables as $el) {

            if (strpos($el->name, $full_name) === 0){
                Schema::drop($el->name);                    // удаление зависимой таблицы "План сеансов на день" и зависимых от неё таблиц "Билеты на сеанс" (относящихся к данному залу на данный день)
            
                DB::table('halls')->where('hall_name', $hall_name)->decrement('session_planes', 1);    // уменьшаем количество действующих планов сеансов для данного зала на 1 
            }
        }        

        session()->flash('film_msg', true);     // маркер, определяющий, где на странице будут отображаться сессионные сообщения. Если 'true' - то в секции "Сетка сеансов"

        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'План сеансов зала '. '"'. $hall_name .'"' . ' на ' . $sessions_date .' успешно удалён из сетки сеансов');
    }  



    public function infoFilmSession(Request $request) // ОТПРАВИТЬ ИНФОРМАЦИЮ О БИЛЕТАХ НА СЕАНС ФИЛЬМА
    {   
        $session_name = json_encode([DB::table($request->input('table_tickets'))->get()]);

        $temporal_array1 = explode("*" , $request->input('table_tickets'));
        $hall_name = current($temporal_array1);

        $row = DB::table('halls')->where('hall_name', $hall_name)->value('rows');
        $seats = DB::table('halls')->where('hall_name', $hall_name)->value('seats_per_row');
        
        return response([$row, $seats, $session_name], 200);
    }


    public function changeFilmSession(Request $request) // ВНЕСТИ ИЗМЕНЕИЯ В СЕТКУ СЕАНСОВ
    {       
        $request_array = json_decode(($request->input('sessionsarray')), true);
                
        foreach($request_array as $session_el) {

            if ($session_el['action'] === 'add') {  // ДОБАВЛЕНИЕ СЕАНСА
                $hall_name = $session_el['hall_name'];
                $session_date = $session_el['session_date'];
                $film_name = $session_el['film_name'];
                $session_time = $session_el['session_time'];
                
                $full_table_name = $hall_name . '*' . $session_date . $session_time . '_tickets';
                $film_session_name = $hall_name . '*' . $session_date;

                if (Schema::hasTable($full_table_name)) continue; 

                $film = Film::where('film_name', $film_name)->first();
                $hall = Hall::where('hall_name', $hall_name)->first();
                $hall_session_film = HallSessionsPlan::relation($session_date, $hall_name);

                $film_session_table = new FilmTicketsCreate();
                $film_session_table->up($full_table_name);      // создание зависимой таблицы продажи билетов
                $hall_plane = DB::table($hall_name . '_plane')->get();
                
                foreach ($hall_plane as $elem) {    // перенос плана зала в таблицу продажи билетов
                  
                    $seat = FilmTickets::relation( $film_session_name, $session_time);
                    $seat->row = $elem->row;
                    $seat->number = $elem->number;
                    $seat->type = $elem->type;

                    $film->session()->save($seat);      // создание записи в зависимой таблице продажи билетов ("место" -> "фильм")
                }

                $hall_session_film->film_name = $film_name;
                $hall_session_film->film_tickets = $full_table_name;
                $hall_session_film->film_start = $session_time;
                $hall_session_film->film_duration = DB::table('films')->where('film_name', $film_name)->value('film_duration');

                $hall->hallSessionsPlan()->save($hall_session_film);    // создание записи в зависимой таблице "План сеансов на день" ("сеанс" -> "зал")

                DB::table('films')->where('film_name', $film_name)->increment('session_films', 1);    // увеличиваем количество действующих сеансов фильма для данного зала на 1 
            }

            if ($session_el['action'] === 'del') {  // УДАЛЕНИЕ СЕАНСА
                $hall_name = $session_el['hall_name'];
                $session_date = $session_el['session_date'];
                $film_name = $session_el['film_name'];
                $session_time = $session_el['session_time'];
                
                $full_table_name = $hall_name . '*' . $session_date . $session_time . '_tickets';
                $film_session_name = $hall_name . '*' . $session_date;
               
                DB::table($film_session_name)->where('film_name', $film_name)
                        ->where('film_start', $session_time)
                        ->delete();                                // удаление записи в суточном плане сеансов, для данного сеанса
                Schema::drop($full_table_name);                    // удаление зависимой таблицы "Билеты на сеанс" (относящихся к данному залу на данный день)

                DB::table('films')->where('film_name', $film_name)->decrement('session_films', 1);    // уменьшаем количество действующих сеансов фильма для данного зала на 1
            }
        }       

        session()->flash('film_msg', true);     // маркер, определяющий, где на странице будут отображаться сессионные сообщения. Если 'true' - то в секции "Сетка сеансов"

        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', 'Изменения в сетку сеансов успешно добавлены');
    }



    public function changeSaleStatus(Request $request) // ОТКРЫТЬ/ЗАКРЫТЬ ПРОДАЖУ БИЛЕТОВ
    {   
        $sale_status = (bool) $request->input('sale_status');
        $status_msge = "";
        
        if ($sale_status === false) {
            
            DB::table('halls')
                ->update([
                'active' => false   // остановить продажу билетов для всех залов 
            ]);

            $status_msge = "Продажи билетов успешно приостановленны";
        }
        if ($sale_status === true) {
            DB::table('halls')
                ->update([
                'active' => true   // возобновить продажу билетов для всех залов 
            ]);

            $status_msge = "Продажи билетов успешно открыты";
        }

        session()->flash('film_msg', true);     // маркер, определяющий, где на странице будут отображаться сессионные сообщения. Если 'true' - то в секции "Сетка сеансов"
        
        return redirect()->route('admin_main', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate()])->with('success', $status_msge);
    }







    public function show()
    {
        //dd(Todo::all());
        return view('/ToDo/list', ['data' => Todo::/*all()*/paginate()]);

    }

    public function edit($id)
    {
        return view('/ToDo/edit', ['data' => Todo::find($id)]);
    }

    public function update($id)
    {
        return view('/ToDo/update', ['data' => Todo::find($id)]);
    }

    public function updateSubmit($id, CreateRequest $request)
    {
        //dd($request->input('task'));
        $task = Todo::find($id);
        $task->name = $request->input('name');
        $task->task = $request->input('task');

        $task->save();

        return redirect()->route('edit', $id)->with('success', 'Задача успешно обновлена');
    }

    public function delete($id)
    {
        Todo::find($id)->delete();
        return redirect()->route('list')->with('success', 'Задача успешно удалена!');
    }

    public function destroy(Todo $todo)
    {
        //
    }
}
