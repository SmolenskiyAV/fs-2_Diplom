<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\Hall;
use Illuminate\Http\Request;
use App\Http\Requests\CreateRequest;
use App\Http\Requests\CreateHallRequest;
use App\Models\HallBilling;
use App\Models\HallSeatsPlan;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\HallSeatsPlaneCreate;

//use GuzzleHttp\Promise\Create;

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
       
        return redirect()->route('admin_main', ['data' => Hall::paginate()])->with('success', 'Новый зал успешно добавлен');
    }

    public function delHall(Request $request)   //  УДАЛИТЬ ЗАЛ
    {
        $hall_name = $request->input('hall_name');
        Hall::where('hall_name', $hall_name)->delete(); // удаление записи в таблице "Зал"

        Schema::drop($hall_name . '_plane');            // удаление зависимой таблицы "План мест в зале"
                                                
        return redirect()->route('admin_main', ['data' => Hall::paginate()])->with('success', 'Новый зал успешно удалён');
    }

    public function sizeHall(Request $request)   //  ЗАДАТЬ РАЗМЕР ЗАЛА
    {
        $hall_name = $request->input('hall_cfg_size');
        $rows = $request->input('rows');
        $seats_per_row = $request->input('seats_per_row');
        $chair_standart_default = $rows * $seats_per_row;

        if (($chair_standart_default === 0) || ($rows > 40) || ($seats_per_row > 50)) {
            return redirect()->route('admin_main', ['data' => Hall::paginate()]) ->with('baddata', 'Новый размер зала ' . $hall_name . ' не может быть определён! Неверные параметры.');
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

        return redirect()->route('admin_main', ['data' => Hall::paginate(), 'radioBtnPushed' => $hall_name]) ->with('success', 'Размер зала ' . $hall_name . ' успешно изменён');
    }

    public function planeHall(Request $request)   //  ЗАДАТЬ ПЛАНИРОВКУ ЗАЛА
    {
        //dd(json_decode(($request->input('hall_plane')), true));
                
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
                                                      
        return redirect()->route('admin_main', ['data' => Hall::paginate(), 'radioBtnPushed' => $hall_name])->with('success', 'План зала ' . $hall_name . ' успешно изменён');
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
                                                      
        return redirect()->route('admin_main', ['data' => Hall::paginate(), 'radioBtnPushed' => $hall_name])->with('success', 'Цены на места в зале ' . $hall_name . ' успешно изменены');
    }

    public function btnPush($pushedBtn, $section)   // НАВИГАЦИЯ ПО РАДИО-КНОПКАМ
    {   // параметр $section определяет, куда будет перемещён скролл страницы после нажатия одной из радиокнопок
        return view('/layouts/app_admin', ['data' => Hall::paginate(), 'radioBtnPushed' => $pushedBtn, 'section' => $section]);
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
