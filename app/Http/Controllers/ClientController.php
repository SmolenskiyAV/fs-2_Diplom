<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hall;
use App\Models\Film;

class ClientController extends Controller
{
    public function btnDatePush($sessions_date)   // НАВИГАЦИЯ ПО КНОПКАМ ДАТЫ СЕАНСОВ
    {           
        return view('/layouts/app_client', ['dataHalls' => Hall::paginate(), 'sessions_date' => $sessions_date, 'dataFilms' => Film::paginate()]);
    }

    public function btnTimePush($film_start, $film_name, $hall_name, $tickets_table)   // НАВИГАЦИЯ ПО КНОПКАМ ВРЕМЯ СЕАНСОВ
    {           
        return view('/inc/app_hall', ['film_start' => $film_start, 'film_name' => $film_name, 'hall_name' => $hall_name, 'tickets_table' => $tickets_table]);
    }
}
