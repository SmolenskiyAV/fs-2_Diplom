<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ИдёмВКино</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&amp;subset=cyrillic,cyrillic-ext,latin-ext" rel="stylesheet">
    @vite(['resources/css/admin/styles.scss'])
</head>

<body>
<header class="page-header">
    <h1 class="page-header__title">Идём<span>в</span>кино</h1>
    <span class="page-header__subtitle">Администраторррская</span>
</header>

<main class="conf-steps">
    @php    
        $hall_name_cfg ='';
        $hall_rows_cfg = 0;
        $hall_seats_per_row_cfg =0;
        if (empty($radioBtnPushed)) $radioBtnPushed = '';
        if (empty($section)) $section = 0;
        $film_name_cfg ='';
        $film_duration = 0;
        $poster_path = '';
        $planedHallName = '';
        $planedHallDate = '';
        $randomID = '';
        $status_color = 'black';
        
        function generateRandomString($length = 10) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $randomString;
        }
    @endphp    
    <section class="conf-step" id="Halls_Control">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Управление залами</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Доступные залы:</p>
            <ul class="conf-step__list">
                @foreach($dataHalls as $el)
                    <li>{{ $el->hall_name }}
                        <button class="conf-step__button conf-step__button-trash"></button>
                    </li>
                @endforeach                
            </ul>
            <button class="conf-step__button conf-step__button-accent">Создать зал</button>
        </div>

        @if(session()->missing('film_msg'))
            @include('inc.massages')
        @endif
        
        <div class="popup" id="Halls_Create">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Добавление зала
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>                        
                    </div>
                    <p class="conf-step__paragraph2 conf-step__legend" style="margin-left: 15px; margin-top: 7px">Для имени зала используй только латинские буквы, цифры, точку или тире!.</p>
                    <div class="popup__wrapper">
                        <form action="{{route('addHall')}}" method="post" accept-charset="utf-8">
                            @csrf
                            <label class="conf-step__label conf-step__label-fullsize" for="hall_name">
                                Название зала
                                <input class="conf-step__inputв" type="text" placeholder="Например, &laquo;Зал 1&raquo;" name="hall_name" onkeypress="return (event.charCode > 64 && event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode > 44 && event.charCode < 47) || (event.charCode > 47 && event.charCode < 58)" required>
                            </label>
                            <div class="conf-step__buttons text-center">
                                <button type="submit" value="Добавить зал" class="conf-step__button conf-step__button-accent">Добавить зал</button>
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" id="Halls_Delete">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Удаление зала
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form action="{{ route('delHall') }}" method="get" accept-charset="utf-8">
                            <p class="conf-step__paragraph">Вы действительно хотите удалить зал <span></span>?</p>
                            
                            <div class="conf-step__buttons text-center">
                                <input type="hidden" name="hall_name" value="">
                                <input type="submit" value="Удалить" class="conf-step__button conf-step__button-accent">
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="conf-step" id="Hall_Config">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Конфигурация залов</h2>
        </header>
        
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
            <ul class="conf-step__selectors-box">
                @if ($radioBtnPushed === '')
                    @foreach($dataHalls as $el)
                        @if ($loop->first)
                            <li><a href="{{ route('btnPush', [$el->hall_name, 2]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cfg" value="{{$el->hall_name}}" checked><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>
                            @php
                                $hall_name_cfg = $el->hall_name;
                                $hall_rows_cfg = $el->rows;
                                $hall_seats_per_row_cfg = $el->seats_per_row;                           
                            @endphp
                            @continue
                        @endif
                        <li><a href="{{ route('btnPush', [$el->hall_name, 2]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cfg" value="{{$el->hall_name}}"><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>        
                    @endforeach
                @else                    
                    @foreach($dataHalls as $el)
                        @if ($el->hall_name === $radioBtnPushed)
                            @if ($section == '2')
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => { // после того, как страница загружена  
                                        const HallConfig = document.getElementById('Hall_Config'); 
                                        HallConfig.querySelector('.conf-step__hall').scrollIntoView(false); // скролл страницы к элементу секция "Конфигурация залов"
                                        window.scrollBy(0,80);
                                    });
                                </script>
                            @endif
                            <li><a href="{{ route('btnPush', [$el->hall_name, 2]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cfg" value="{{$el->hall_name}}" checked><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>
                            @php
                                $hall_name_cfg = $radioBtnPushed;
                                $hall_rows_cfg = $el->rows;
                                $hall_seats_per_row_cfg = $el->seats_per_row;                                                          
                            @endphp
                            @continue
                        @endif
                        <li><a href="{{ route('btnPush', [$el->hall_name, 2]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cfg" value="{{$el->hall_name}}"><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>        
                    @endforeach
                @endif
            </ul>
            <p class="conf-step__paragraph">Укажите количество рядов (не более 40) и максимальное количество кресел в ряду (не более 50):</p>
            
            <form action="{{route('sizeHall')}}" method="post" accept-charset="utf-8">
                @csrf
                <div class="conf-step__legend">
                    <label class="conf-step__label">Рядов, шт<input type="text" name="rows" class="conf-step__input" placeholder="10" value="{{ $hall_rows_cfg }}" required></label>
                    <span class="multiplier">x</span>
                    <label class="conf-step__label">Мест, шт<input type="text" name="seats_per_row" class="conf-step__input" placeholder="8" value="{{ $hall_seats_per_row_cfg }}" required></label>
                    <div class="conf-step__buttons">                        
                        <input type="hidden" name="hall_cfg_size" value="{{$hall_name_cfg}}">
                        @if (Schema::hasTable($hall_name_cfg.'_plane'))
                            <p class="conf-step__paragraph" style="color: red">ВНИМАНИЕ! При смене размера зала, его схема сбрасывается в дефолтное состояние.</p>
                            <button type="submit" value="Задать размер зала" class="conf-step__button conf-step__button-accent">Задать размер зала</button>
                        @endif
                    </div>
                </div>    
            </form>
            
            <p class="conf-step__paragraph">Теперь вы можете указать типы кресел на схеме зала:</p>
            <div class="conf-step__legend">
                <span class="conf-step__chair conf-step__chair_standart"></span> — обычные кресла
                <span class="conf-step__chair conf-step__chair_vip"></span> — VIP кресла
                <span class="conf-step__chair conf-step__chair_disabled"></span> — заблокированные (нет кресла)
                <p class="conf-step__hint">Чтобы изменить вид кресла, нажмите по нему левой кнопкой мыши</p>
            </div>
            
            @if (Schema::hasTable($hall_name_cfg.'_plane'))
                @php
                    $plane = DB::table($hall_name_cfg.'_plane')->get();
                @endphp
                <div class="conf-step__hall">                            
                    @for ($s = 1; $s < $hall_seats_per_row_cfg + 1; $s++ )
                        <div class="conf-step__hall-wrapper" style="margin-left: 4px; margin-right: 4px">
                            @for ($r = 1; $r < $hall_rows_cfg + 1; $r++ )
                                @php
                                    $type = $plane->where('row',$r)->where('number',$s)->value('type')
                                @endphp
                            <div style="margin-top: 8px; margin-bottom: 8px">                            
                                @if ($type === 1)
                                    <span class="conf-step__chair conf-step__chair_standart" data-row="{{ $r }}" data-seat="{{ $s }}" data-type=1></span>
                                @elseif ($type === 2)
                                    <span class="conf-step__chair conf-step__chair_vip" data-row="{{ $r }}" data-seat="{{ $s }}" data-type=2></span>
                                @else
                                    <span class="conf-step__chair conf-step__chair_disabled" data-row="{{ $r }}" data-seat="{{ $s }}" data-type=0></span>
                                @endif
                            </div>
                            @endfor
                        </div>
                    @endfor
                </div>
            
                <fieldset class="conf-step__buttons text-center">                    
                    <form action="{{route('planeHall')}}" method="post" accept-charset="utf-8">
                        @csrf
                        <button class="conf-step__button conf-step__button-regular" style="margin-right: 15px">Отмена</button>
                        <input type="hidden" name="hall_cfg_name" value="{{$hall_name_cfg}}">
                        <input type="hidden" name="hall_plane" value="">
                        <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
                    </form>
                </fieldset>

            @else
                <div class="conf-step__hall">
                    <div class="conf-step__hall-wrapper" style="margin-left: 4px; margin-right: 4px">
                    </div>
                </div>
            @endif

        </div>
    </section>

    <section class="conf-step" id="Cost_Config">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Конфигурация цен</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Выберите зал для конфигурации:</p>
            <ul class="conf-step__selectors-box">
                @if ($radioBtnPushed === '')
                    @foreach($dataHalls as $el)
                        @if ($loop->first)
                            <li><a href="{{ route('btnPush', [$el->hall_name, 3]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cost" value="{{$el->hall_name}}" checked><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>
                            @continue
                        @endif
                        <li><a href="{{ route('btnPush', [$el->hall_name, 3]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cost" value="{{$el->hall_name}}"><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>        
                    @endforeach
                @else                    
                    @foreach($dataHalls as $el)
                        @if ($el->hall_name === $radioBtnPushed)
                            @if ($section == '3')
                                <script>
                                    document.addEventListener('DOMContentLoaded', () => { // после того, как страница загружена  
                                        const CostConfig = document.getElementById('Cost_Config');
                                        CostConfig.scrollIntoView(false); // скролл страницы к элементу секция "Конфигурация цен"
                                        window.scrollBy(0,80);
                                    });
                                </script>
                            @endif
                            <li><a href="{{ route('btnPush', [$el->hall_name, 3]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cost" value="{{$el->hall_name}}" checked><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>
                            @continue
                        @endif
                        <li><a href="{{ route('btnPush', [$el->hall_name, 3]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cost" value="{{$el->hall_name}}"><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>        
                    @endforeach
                @endif
            </ul>

            <p class="conf-step__paragraph">Установите цены для типов кресел:</p>
            <form action="{{route('billingHall')}}" method="post" accept-charset="utf-8">
                @csrf
                <div class="conf-step__legend">
                    <label class="conf-step__label">Цена, рублей<input type="text" name="hall_usual_cost" class="conf-step__input" placeholder="0" value="{{ DB::table('halls_billing')->where('hall_name', $hall_name_cfg)->value('usual_cost') }}"></label>
                        за <span class="conf-step__chair conf-step__chair_standart"></span> обычные кресла
                </div>
                <div class="conf-step__legend">
                    <label class="conf-step__label">Цена, рублей<input type="text" name="hall_vip_cost" class="conf-step__input" placeholder="0" value="{{ DB::table('halls_billing')->where('hall_name', $hall_name_cfg)->value('vip_cost') }}"></label>
                        за <span class="conf-step__chair conf-step__chair_vip"></span> VIP кресла
                </div>

                <fieldset class="conf-step__buttons text-center">
                    <input type="hidden" name="hall_cfg_cost" value="{{$hall_name_cfg}}">
                    @if (DB::table('halls_billing')->where('hall_name', $hall_name_cfg)->exists())
                        <button class="conf-step__button conf-step__button-regular" style="margin-right: 15px">Отмена</button>                                     
                        <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
                    @endif               
                </fieldset>
            </form>
        </div>
    </section>

    <section class="conf-step" id="Seance_Config">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Сетка сеансов</h2>
        </header>

        @if(session()->has('film_msg'))
            @include('inc.massages')
            <script>
                document.addEventListener('DOMContentLoaded', () => { // после того, как страница загружена  
                    const SeanceConfig = document.getElementById('Seance_Config'); 
                    SeanceConfig.scrollIntoView(); // скролл страницы к элементу секция "Сетка сеансов"
                    window.scrollBy(0,-20);
                });
            </script>
        @endif
        
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">
                <button class="conf-step__button conf-step__button-accent">Добавить фильм</button>
            </p>
            <p class="conf-step__paragraph2 conf-step__legend">Для удаления фильма кликните по полю с его описанием.</p>
            <div class="conf-step__movies">
                @foreach($dataFilms as $el)               
                    <div class="conf-step__movie">
                        <img class="conf-step__movie-poster" alt="poster" data-name="{{ $el->film_name }}" src={{asset("$el->poster_path")}}>
                        <h3 class="conf-step__movie-title">{{ $el->film_name }}</h3>
                        <p class="conf-step__movie-duration" data-name="{{ $el->film_name }}">{{ $el->film_duration }} минут</p>                        
                    </div>                
                @endforeach
            </div>           

            @if (Schema::hasTable($hall_name_cfg.'_plane'))
                <p class="conf-step__paragraph">
                    <button class="conf-step__button conf-step__button-accent">Добавить суточный план</button>
                </p>
            @endif

            <p class="conf-step__paragraph2 conf-step__legend" style="margin-bottom: 2px;">Для добавления новых сеансов в суточный план кликните по названию зала.</p>
            <p class="conf-step__paragraph2 conf-step__legend">Для удаления сеанса фильма (внутри суточного плана) кликните по иконке сеанса.</p>
            
            @php
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
                    }
                    
                }
            @endphp

            <div class="conf-step__seances"> 
                @foreach($sessionsPlanTables as $el1)
                    @php
                        $temporal_array = explode("*" , $el1);
                        $planedHallName = current($temporal_array);
                        $planedHallDate = end($temporal_array);
                        $filmSessions = DB::table($el1)->get();
                        $hallStatus = DB::table('halls')->where('hall_name', $planedHallName)->value('active');
                        if ($hallStatus) {
                            $status_color = 'green';    // зал открыт для продаж
                        } else {
                            $status_color = 'red';      // зал закрыт для продаж
                        }
                        $randomID = generateRandomString();
                    @endphp
                    <div class="conf-step__seances-hall">
                        <div style="display: flex;">
                            <span href="#" style="text-decoration: none; color: black; margin-right: 5px"><h3 class="conf-step__seances-title" style="cursor:pointer">{{ $planedHallName }}</h3><small style="cursor:default">{{ $planedHallDate }}</small></span>
                            <span name="statusMarker" data-color="{{ $status_color }}" style="font-size: 200%; margin-right: 10px; cursor: pointer;">&#36;</span>
                            <button class="conf-step__button conf-step__button-trash" data-planedhallname="{{ $planedHallName }}" data-planedhalldate="{{ $planedHallDate }}" data-fullplanedname="{{ $el1 }}"></button>                        
                        </div>

                        <div class="conf-step__seances-timeline">
                            @foreach($filmSessions as $el2)
                               
                                @php
                                    $filmSessionName = $el2->film_name;
                                    $posterBackground_path = DB::table('films')->where('file_name', $filmSessionName)->value('poster_path');
                                    $filmSessionStart = $el2->film_start;
                                    $filmSessionDuration = $el2->film_duration;
                                    $filmSessionTickets = $el2->film_tickets;

                                    $hours = $filmSessionStart->format('H');
                                    $minutes = $filmSessionStart->format('i');
                                    $left = ((int) $hours * 60) + ((int) $minutes * 0.5);
                                    $width = (int) $filmSessionDuration * 0.5;
                                    $stop_pixel = $left + $width;
                                @endphp

                                <div class="conf-step__seances-movie" name="filmSession" data-tickets="{{ $filmSessionTickets }}" data-startpixel="{{ $left }}" data-stoppixel="{{ $stop_pixel }}" style="background-color: rgb(133, 255, 137); cursor: pointer;">
                                    <img class="conf-step__movie-poster" style="width: 100%; height: 100%; top: 0; left: 0; position: absolute;" alt="poster" src={{asset("$posterBackground_path")}}>    
                                    <!--<p class="conf-step__seances-movie-title">{{ $filmSessionName }}</p>-->
                                    <p class="conf-step__seances-movie-start">{{ $filmSessionStart }}</p>
                                </div>
                            @endforeach                           
                        </div>
                    </div>
                @endforeach
                
                <script>// вынужден использовать эту шнягу, т.к. прямая инжекция чрз двойные фиг.скобки от php в style="" не работает, блинн :-/
                            const HallPlanesMarkers = document.querySelectorAll('span[name="statusMarker"]');
                            const FilmSessions = document.querySelectorAll('span[name="filmSession"]');                          
                            if (HallPlanesMarkers) {
                                let color = '';
                                for (let j = 0; j < HallPlanesMarkers.length; j++){  // раскрашиваем маркер "$" каждого Плана сеансов
                                    color = HallPlanesMarkers[j].dataset.color;      // в зависимости от того, открыт зал для продаж или нет
                                    HallPlanesMarkers[j].style.color = color;                                  
                                }
                            }
                            if (FilmSessions) {
                                let startpixel = '';
                                let width = '';
                                for (let j = 0; j < FilmSessions.length; j++){            // смещаем каждый сеанс внутри суточного плана
                                    startpixel = FilmSessions[j].dataset.startpixel;      // в зависимости от установленного начального времени 
                                    width = FilmSessions[j].dataset.stoppixel - startpixel;
                                    FilmSessions[j].style.left = `${startpixel}px`;
                                    FilmSessions[j].style.width = `${width}px`;          // задаём ширину блока в зависимости от продолжительности сеанса                       
                                }
                            }
                </script>

            </div>

            <div class="conf-step__seances">                
                <div class="conf-step__seances-hall">                    
                    <div style="display: flex;">
                        <span style="text-decoration: none; color: black; margin-right: 5px"><h3 class="conf-step__seances-title" style="cursor:pointer">Зал 1</h3><small style="cursor:default">2023-06-1</small></span>
                        <span style="color: black; font-size: 200%; margin-right: 10px; cursor: pointer">&#36;</span>
                        <button class="conf-step__button conf-step__button-trash"></button>
                    </div>
                    <div class="conf-step__seances-timeline">
                        <div class="conf-step__seances-movie" style="width: 60px; background-color: rgb(133, 255, 137); left: 0px; cursor: pointer;">
                            <!--<p class="conf-step__seances-movie-title" style="font-size: 90%;">Миссия выполнима</p>-->
                            <img class="conf-step__movie-poster" style="width: 100%; height: 100%; top: 0; left: 0; position: absolute;" alt="poster" src={{asset("storage/images/films/Джун_камикадзе_poster.jpg")}}>
                            <p class="conf-step__seances-movie-start">00:00</p>
                        </div>
                        <div class="conf-step__seances-movie" style="width: 60px; background-color: rgb(133, 255, 137); left: 360px;">
                            <p class="conf-step__seances-movie-title">Миссия выполнима</p>
                            <p class="conf-step__seances-movie-start">12:00</p>
                        </div>
                        <div class="conf-step__seances-movie" style="width: 65px; background-color: rgb(202, 255, 133); left: 420px;">
                            <p class="conf-step__seances-movie-title">Звёздные войны XXIII: Атака клонированных клонов</p>
                            <p class="conf-step__seances-movie-start">14:00</p>
                        </div>
                    </div>                    
                </div>
                <div class="conf-step__seances-hall">
                    <h3 class="conf-step__seances-title">Зал 2</h3>
                    <div class="conf-step__seances-timeline">
                        <div class="conf-step__seances-movie" style="width: 65px; background-color: rgb(202, 255, 133); left: 595px;">
                            <p class="conf-step__seances-movie-title">Звёздные войны XXIII: Атака клонированных клонов</p>
                            <p class="conf-step__seances-movie-start">19:50</p>
                        </div>
                        <!--<div class="conf-step__seances-movie" style="width: 60px; background-color: rgb(133, 255, 137); left: 660px;">
                            <p class="conf-step__seances-movie-title">Миссия выполнима</p>
                            <p class="conf-step__seances-movie-start">22:00</p>
                        </div>-->
                    </div>
                </div>
            </div>

            <fieldset class="conf-step__buttons text-center">
                <button class="conf-step__button conf-step__button-regular">Отмена</button>
                <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
            </fieldset>
        </div>

        <div class="popup" id="Films_Add">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Добавление фильма
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form action="{{route('addFilm')}}" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                            @csrf
                            <label class="conf-step__label conf-step__label-fullsize" for="film_name">
                                Название фильма
                                <input class="conf-step__inputв" type="text" placeholder="Например, &laquo;Эммануэль&raquo;" name="film_name" value="{{ old('film_name') }}" required>
                            </label>
                            <label class="conf-step__label conf-step__label-fullsize" for="film_duration">
                                Продолжительность фильма в минутах:
                                <input class="conf-step__inputв" type="number" name="film_duration" value="{{ old('film_duration') }}" min="10" max="180" required>
                            </label>
                            <label class="conf-step__label conf-step__label-fullsize" for="poster">
                                Постер фильма jpg, png, bmp, jpeg, svg
                                <input class="conf-step__inputв" type="file" name="poster" value="{{ old('poster') }}" required>
                            </label>
                            <div class="conf-step__buttons text-center">
                                <button type="submit" value="Добавить фильм" class="conf-step__button conf-step__button-accent">Добавить фильм</button>
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" id="Films_Delete">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Удаление фильма
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form action="{{ route('delFilm') }}" method="get" accept-charset="utf-8">
                            <p class="conf-step__paragraph">Вы действительно хотите удалить фильм "<span></span>"?</p>
                            
                            <div class="conf-step__buttons text-center">
                                <input type="hidden" name="film_name" value="">
                                <input type="submit" value="Удалить" class="conf-step__button conf-step__button-accent">
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" id="HallSessionsPlan_Add">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Добавление плана в сетку сеансов
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form action="{{route('addSessionsPlan')}}" method="post" enctype="multipart/form-data" accept-charset="utf-8">
                            @csrf
                            <label class="conf-step__label conf-step__label-fullsize" for="hall_name">
                                Название зала                                
                            </label>
                            <select class="conf-step__inputв" name="hall_name">
                                @foreach($dataHalls as $el)
                                    <option value="{{ $el->hall_name }}">{{ $el->hall_name }}</option>
                                @endforeach
                            </select>
                            <label class="conf-step__label conf-step__label-fullsize" for="sessions_date">
                                Дата плана в сетке сеансов:
                                <input class="conf-step__inputв" type="date" name="sessions_date" required>
                            </label>
                                                   
                            <div class="conf-step__buttons text-center">
                                <button type="submit" value="Добавить план" class="conf-step__button conf-step__button-accent">Добавить план</button>
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" id="SessionsPlane_Delete">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Удаление плана сеансов
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form action="{{ route('delSessionsPlan') }}" method="get" accept-charset="utf-8">
                            <p class="conf-step__paragraph">Вы действительно хотите удалить план сенсов зала <span name="planedHallName"></span>?</p>
                            <p class="conf-step__paragraph">Дата плана: <span name="planedHallDate"></span>.</p>
                            
                            <div class="conf-step__buttons text-center">
                                <input type="hidden" name="hallPlanedName" value="">
                                <input type="hidden" name="hallPlanedDate" value="">
                                <input type="hidden" name="fullPlanedName" value="">
                                <input type="submit" value="Удалить" class="conf-step__button conf-step__button-accent">
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="popup" id="FilmSession_Add">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Добавление сеанса фильма в суточный план
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form accept-charset="utf-8">
                            @csrf
                            <p class="conf-step__label conf-step__label-fullsize" style="font-size: 150%">Суточный план зала "<span name="hall_name"></span>" на <span name="film_date"></span>.</p>
                            <label class="conf-step__label conf-step__label-fullsize" for="film_name" style="font-size: 150%; margin-top: 20px">
                                Название фильма                                
                            </label>
                            <select class="conf-step__inputв" name="film_name">
                                @foreach($dataFilms as $el)
                                    <option value="{{ $el->film_name }}">{{ $el->film_name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="session_date" value="">
                            <input type="hidden" name="hall_planed_name" value="">
                            <label class="conf-step__label conf-step__label-fullsize" for="session_time" style="font-size: 150%; margin-top: 10px">
                                Время начала сеанса:
                                <input class="conf-step__inputв" type="time" name="session_time" required>
                            </label>                       
                            <div class="conf-step__buttons text-center">
                                <button type="submit" value="Добавить сеанс" class="conf-step__button conf-step__button-accent">Добавить сеанс</button>
                                <button class="conf-step__button conf-step__button-regular">Отменить</button>            
                            </div>
                            <span name="popupWarning1" style="display: none; color: red; margin-top: 15px; font-size: 150%">Заполни поле "Время начала сеанса"!</span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>    

    <section class="conf-step" id="Sale_Status">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Открыть продажи</h2>
        </header>
        <div class="conf-step__wrapper text-center">
            <p class="conf-step__paragraph">Всё готово, теперь можно:</p>
            <button class="conf-step__button conf-step__button-accent">Открыть продажу билетов</button>
        </div>
    </section>
</main>



@vite('resources/js/admin/accordeon.js')
</body>
</html>
