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
    @endphp    
    <section class="conf-step" id="Halls_Control">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Управление залами</h2>
        </header>
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">Доступные залы:</p>
            <ul class="conf-step__list">
                @foreach($data as $el)
                    <li>{{ $el->hall_name }}
                        <button class="conf-step__button conf-step__button-trash"></button>
                    </li>
                @endforeach                
            </ul>
            <button class="conf-step__button conf-step__button-accent">Создать зал</button>
        </div>

        @include('inc.massages')
        
        <div class="popup" id="Halls_Create">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__header">
                        <h2 class="popup__title">
                            Добавление зала
                            <a class="popup__dismiss" href="#"><img src={{asset('storage/images/admin/close.png')}} alt="Закрыть"></a>
                        </h2>

                    </div>
                    <div class="popup__wrapper">
                        <form action="{{route('addHall')}}" method="post" accept-charset="utf-8">
                            @csrf
                            <label class="conf-step__label conf-step__label-fullsize" for="hall_name">
                                Название зала
                                <input class="conf-step__inputв" type="text" placeholder="Например, &laquo;Зал 1&raquo;" name="hall_name" required>
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
                    @foreach($data as $el)
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
                    @foreach($data as $el)
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
                    @foreach($data as $el)
                        @if ($loop->first)
                            <li><a href="{{ route('btnPush', [$el->hall_name, 3]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cost" value="{{$el->hall_name}}" checked><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>
                            @continue
                        @endif
                        <li><a href="{{ route('btnPush', [$el->hall_name, 3]) }}" style="text-decoration: none; color: black"><input type="radio" class="conf-step__radio" name="chairs-hall-cost" value="{{$el->hall_name}}"><span class="conf-step__selector">{{$el->hall_name}}</span></a></li>        
                    @endforeach
                @else                    
                    @foreach($data as $el)
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
        <div class="conf-step__wrapper">
            <p class="conf-step__paragraph">
                <button class="conf-step__button conf-step__button-accent">Добавить фильм</button>
            </p>
            <div class="conf-step__movies">
                <div class="conf-step__movie">
                    <img class="conf-step__movie-poster" alt="poster" src={{asset('storage/images/films/poster.png')}}>
                    <h3 class="conf-step__movie-title">Звёздные войны XXIII: Атака клонированных клонов</h3>
                    <p class="conf-step__movie-duration">130 минут</p>
                </div>

                <div class="conf-step__movie">
                    <img class="conf-step__movie-poster" alt="poster" src={{asset('storage/images/films/poster.png')}}>
                    <h3 class="conf-step__movie-title">Миссия выполнима</h3>
                    <p class="conf-step__movie-duration">120 минут</p>
                </div>

                <div class="conf-step__movie">
                    <img class="conf-step__movie-poster" alt="poster" src={{asset('storage/images/films/poster.png')}}>
                    <h3 class="conf-step__movie-title">Серая пантера</h3>
                    <p class="conf-step__movie-duration">90 минут</p>
                </div>

                <div class="conf-step__movie">
                    <img class="conf-step__movie-poster" alt="poster" src={{asset('storage/images/films/poster.png')}}>
                    <h3 class="conf-step__movie-title">Движение вбок</h3>
                    <p class="conf-step__movie-duration">95 минут</p>
                </div>

                <div class="conf-step__movie">
                    <img class="conf-step__movie-poster" alt="poster" src={{asset('storage/images/films/poster.png')}}>
                    <h3 class="conf-step__movie-title">Кот Да Винчи</h3>
                    <p class="conf-step__movie-duration">100 минут</p>
                </div>
            </div>

            <div class="conf-step__seances">
                <div class="conf-step__seances-hall">
                    <h3 class="conf-step__seances-title">Зал 1</h3>
                    <div class="conf-step__seances-timeline">
                        <div class="conf-step__seances-movie" style="width: 60px; background-color: rgb(133, 255, 137); left: 0;">
                            <p class="conf-step__seances-movie-title">Миссия выполнима</p>
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
                        <div class="conf-step__seances-movie" style="width: 60px; background-color: rgb(133, 255, 137); left: 660px;">
                            <p class="conf-step__seances-movie-title">Миссия выполнима</p>
                            <p class="conf-step__seances-movie-start">22:00</p>
                        </div>
                    </div>
                </div>
            </div>

            <fieldset class="conf-step__buttons text-center">
                <button class="conf-step__button conf-step__button-regular">Отмена</button>
                <input type="submit" value="Сохранить" class="conf-step__button conf-step__button-accent">
            </fieldset>
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
