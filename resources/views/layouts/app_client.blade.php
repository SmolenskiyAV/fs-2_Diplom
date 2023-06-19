<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>ИдёмВКино</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
  @vite(['resources/css/client/styles.scss'])
</head>

@php
    $poster_path = '';
    $actualSessionsDays = [];
    $actualFilms = [];
    $actualHalls = [];
    $film_start_result =[];
    if (empty($sessions_date)) {
        $currentPlaneDate = '';
    } else $currentPlaneDate = $sessions_date;
    
    $allTables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name;");
    $sessionsPlanTables = [];      // список всех таблиц планов сеансов в БД
    $sessionsDayPlanTables = [];   // список всех таблиц сеансов в БД 
    
    foreach($allTables as $el) {

        if (preg_match("/(_tickets)/", $el->name)){
            $sessionsDayPlanTables[] = $el->name;
            continue;
        }
        if (preg_match("/.+(\*).+/", $el->name)){
            $sessionsPlanTables[] = $el->name;

            $temporal_array = explode("*" , $el->name);
            $planedHallDate = end($temporal_array);
            $temp_actualSessionsDays[] = $planedHallDate;
        }
    }
    
    $actualSessionsDays = array_unique($temp_actualSessionsDays);   // убираем повторяющиеся даты    
    
    function compareByTimeStamp($time1, $time2) // сортировка массива дат
    {
        if (strtotime($time1) > strtotime($time2))
        return 1;
        else if (strtotime($time1) < strtotime($time2)) 
        return -1;
        else
        return 0;
    }
    
    usort($actualSessionsDays, "compareByTimeStamp");  // сортируем массив дат по возрастанию
    
@endphp

<body>
    <header class="page-header">
        <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    </header>
  
    <nav class="page-nav">
        @php
            $today_obj = getdate();
            $today_month = $today_obj['mon'];

            if ((int) $today_month < 10) $today_month = str_pad($today_month, 2, "0", STR_PAD_LEFT);    // если месяц = один разряд, дополняем его нулём        
            $today = $today_obj['year'] . '-' . $today_month . '-' . $today_obj['mday'];
        @endphp
        <a class="page-nav__day page-nav__day_today" href="{{ route('btnDatePush', $today) }}">
        <span class="page-nav__day-week"></span><span class="page-nav__day-number"></span><span>&#160;</span><span></span>
        </a>    

        @foreach($actualSessionsDays as $el)
            @php
                $time = strtotime($el);     //Перевод даты в timestamp
                $day_week_number = date('w', $time);
                $actual_day_number = (int) mb_strimwidth($el, 8, 2);
                $month_number = date('m', $time);

                switch ($day_week_number) {
                    case 1:
                        $actual_day = "Пн";
                        break;
                    case 2:
                        $actual_day = "Вт";
                        break;
                    case 3:
                        $actual_day = "Ср";
                        break;
                    case 4:
                        $actual_day = "Чт";
                        break;
                    case 5:
                        $actual_day = "Пт";
                        break;
                    case 6:
                        $actual_day = "Сб";
                        break;
                    case 0:
                        $actual_day = "Вс";
                        break;
                }

                switch ($month_number) {
                    case '01':
                        $actual_month = "Янв";
                        break;
                    case '02':
                        $actual_month = "Фев";
                        break;
                    case '03':
                        $actual_month = "Мар";
                        break;
                    case '04':
                        $actual_month = "Апр";
                        break;
                    case '05':
                        $actual_month = "Май";
                        break;
                    case '06':
                        $actual_month = "Июн";
                        break;
                    case '07':
                        $actual_month = "Июл";
                        break;
                    case '08':
                        $actual_month = "Авг";
                        break;
                    case '09':
                        $actual_month = "Сент";
                        break;
                    case '10':
                        $actual_month = "Окт";
                        break;
                    case '11':
                        $actual_month = "Нояб";
                        break;
                    case '12':
                        $actual_month = "Дек";
                        break;
                }

            @endphp
            @if ($currentPlaneDate === '')
                @if ($loop->first)
                    @php
                    $currentPlaneDate = $el;
                    @endphp
                    <a class="page-nav__day page-nav__day_chosen" href="{{ route('btnDatePush', $currentPlaneDate) }}" style='pointer-events: none; cursor: pointer'>
                        <span class="page-nav__day-week" style='cursor: pointer'>{{ $actual_day }}</span><span class="page-nav__day-number" data-planedate="{{ $currentPlaneDate }}">{{ $actual_day_number }}</span><span style='pointer-events: none;'>{{ $actual_month }}</span>
                    </a>
                    @continue
                @endif
                <a class="page-nav__day" href="{{ route('btnDatePush', $el) }}" style='pointer-events: none; cursor: pointer'>
                    <span class="page-nav__day-week" style='cursor: pointer'>{{ $actual_day }}</span><span class="page-nav__day-number" data-planedate="{{ $el }}">{{ $actual_day_number }}</span><span style='pointer-events: none;'>{{ $actual_month }}</span>
                </a>
            @else
                @if ($currentPlaneDate === $el)
                    <a class="page-nav__day page-nav__day_chosen" href="{{ route('btnDatePush', $currentPlaneDate) }}" style='pointer-events: none; cursor: pointer'>
                        <span class="page-nav__day-week" style='pointer-events: none; cursor: pointer'>{{ $actual_day }}</span><span class="page-nav__day-number" data-planedate="{{ $currentPlaneDate }}">{{ $actual_day_number }}</span style='pointer-events: none;'><span>{{ $actual_month }}</span>
                    </a>
                    @continue
                @endif
                <a class="page-nav__day" href="{{ route('btnDatePush', $el) }}" style='cursor: pointer'>
                    <span class="page-nav__day-week" style='cursor: pointer'>{{ $actual_day }}</span><span class="page-nav__day-number" data-planedate="{{ $el }}">{{ $actual_day_number }}</span><span style='pointer-events: none;'>{{ $actual_month }}</span>
                </a>
            @endif
        @endforeach    
        <a class="page-nav__day page-nav__day_next" href="#">
        </a>
    </nav>            
    
    @php
        use App\Models\FilmTickets;

        foreach($sessionsDayPlanTables as $el) {
            if(str_contains($el, $currentPlaneDate)) {

                $temp_seat = DB::table($el)->get();
                //dd($temp_seat[0]->film_id);
                $acualFilm = $temp_seat[0]->film_id;
                $actualFilms[] = DB::table('films')->where('id', $acualFilm)->value('film_name');   // список всех экспонируемых в данный день фильмов
                
                $temporal_array1 = explode("*" , $el);                    
                $actualHalls[] = current($temporal_array1);    // список всех работающих в данный день залов
            }
        }
        $actualFilms = array_unique($actualFilms);   // убираем повторяющиеся фильы
        $actualHalls = array_unique($actualHalls);   // убираем повторяющиеся залы               
    @endphp    

    <main>
        @foreach($actualFilms as $el)
            @php
                    $poster_path = DB::table('films')->where('film_name', $el)->value('poster_path');
                    $film_description = DB::table('films')->where('film_name', $el)->value('description');
                    $film_duration = DB::table('films')->where('film_name', $el)->value('film_duration');
                    $country_source = DB::table('films')->where('film_name', $el)->value('country_source');                
            @endphp
            <section class="movie">
                <div class="movie__info">
                    <div class="movie__poster">
                    <img class="movie__poster-image" alt="{{ $el }} постер" src={{asset("$poster_path")}}>
                    </div>
                    <div class="movie__description">
                    <h2 class="movie__title">{{ $el }}</h2>
                    <p class="movie__synopsis">{{ $film_description }}</p>
                    <p class="movie__data">
                        <span class="movie__data-duration">{{ $film_duration }} минут</span>
                        <span class="movie__data-origin">{{ $country_source }}</span>
                    </p>
                    </div>
                </div>  
                
                @foreach($actualHalls as $el1)
                    @php
                        $current_ticket_table = $el1 . '*' . $currentPlaneDate;
                        $film_starts = DB::table($current_ticket_table)->pluck('film_start');
                        
                        foreach($film_starts as $film_start) {
                            $film_start_result[] = $film_start;
                        }
                        $film_start_result = array_unique($film_start_result);
                        sort($film_start_result);                               // сортируем кнопки "время сеанса" по возрастанию
                    @endphp
                    <div class="movie-seances__hall">
                        <h3 class="movie-seances__hall-title"><span style="font-weight: normal">Зал </span> {{ $el1 }}</h3>
                        <ul class="movie-seances__list">                 
                        
                        @foreach($film_start_result as $film_start) 
                            @if(DB::table($current_ticket_table)->where('film_start', $film_start)->value('film_name') === $el)
                                <li class="movie-seances__time-block"><a class="movie-seances__time" href="{{ route('btnTimePush', [$film_start, $el, $el1, DB::table($current_ticket_table)->where('film_start', $film_start)->value('film_tickets')]) }}">{{ $film_start }}</a></li>
                            @endif
                        @endforeach                    
                        </ul>
                    </div>
                @endforeach      
            </section>
        @endforeach      
    </main>

@vite('resources/js/client/accordeon2.js')  
</body>
</html>