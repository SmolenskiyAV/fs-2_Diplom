<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\Film;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\DB;


class ClientController extends Controller
{
    public function btnDatePush($sessions_date, $start_element)   // НАВИГАЦИЯ ПО КНОПКАМ ДАТЫ СЕАНСОВ
    {          
        $hall_blocked = false;     // маркер
        return view('/layouts/app_client', ['dataHalls' => Hall::paginate(), 'sessions_date' => $sessions_date, 'dataFilms' => Film::paginate(), 'hall_blocked' => $hall_blocked, 'start_element' => $start_element]);
    }

    public function btnTimePush($film_start, $film_name, $hall_name, $film_date, $tickets_table)   // НАВИГАЦИЯ ПО КНОПКАМ ВРЕМЯ СЕАНСОВ
    {           
        return view('/inc/app_hall', ['film_start' => $film_start, 'film_name' => $film_name, 'hall_name' => $hall_name, 'film_date' => $film_date, 'tickets_table' => $tickets_table]);
    }

    public function getTicketCode(Request $request)   // ГЕНЕРАЦИЯ КОДА БРОНИРОВАНИЯ БИЛЕТОВ
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

            return view('/inc/app_ticket', ['seats_list' => $seats_list, 'film_name' => $film_name, 'hall_name' => $hall_name, 'session_time' => $session_time, 'qrimage' => $qrimage]);
        } else {
            
            $hall_blocked = true;     // маркер, при наличии к-го выводится сообщ о закрытой продаже билетов

            return view('/layouts/app_client', ['dataHalls' => Hall::paginate(), 'dataFilms' => Film::paginate(), 'hall' => $hall_name, 'hall_blocked' => $hall_blocked]);
        }
    }
}
