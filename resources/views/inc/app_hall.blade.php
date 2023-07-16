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

<body>
    <header class="page-header">
    <a href="{{ route('client_main') }}" style="text-decoration: none"><h1 class="page-header__title">Идём<span>в</span>кино</h1></a>
    </header>
    @php
        if (empty($film_start)) $film_start = '';
        if (empty($film_name)) $film_name = '';
        if (empty($hall_name)) $hall_name = '';
        if (empty($tickets_table)) {
            $tickets_table = '';
            $render_seats = '';
            $rows = 0;
            $film_date ='';                 
        } else {
            $tickets_obj = DB::table($tickets_table)->get();
            
            $rows = 1;
            $seats_per_row =1;
            $i = 0;
            $counter = 1;
                                
            foreach ($tickets_obj as $seat) {                
                $rowVar = $seat->row;
                if($rowVar > $rows)  $rows = $rows +1;   // количество рядов в зале  
                $i++;           
            }                    
            $seats_per_row = $i / $rows;     // количество мест в ряду
        }

        if (Schema::hasTable('halls_billing')) {
            $vip_cost = DB::table('halls_billing')->where('hall_name', $hall_name)->value('vip_cost');
            $usual_cost = DB::table('halls_billing')->where('hall_name', $hall_name)->value('usual_cost');
        }        
    @endphp
        
    <main>
        <section class="buying">
            <div class="buying__info">
                <div class="buying__info-description">
                <h2 class="buying__info-title">{{ $film_name }}</h2>
                <p class="buying__info-start">Начало сеанса: {{ $film_start }}</p>
                <p class="buying__info-hall">{{ $hall_name }}</p>          
                </div>
                <div class="buying__info-hint">
                <p>Тапните дважды,<br>чтобы увеличить</p>
                </div>
            </div>
            <div class="buying-scheme">
                <div class="buying-scheme__wrapper">

                                        
                    @for ($r = 1; $r < $rows + 1; $r++)
                        <div class="buying-scheme__row">
                            @for ($s = 1; $s < $seats_per_row + 1; $s++)
                                @php
                                    $render_seats = DB::table($tickets_table)->where('row', $r)->where('number', $s)->get();

                                    $type = $render_seats[0]->type;                                    
                                    $sold = $render_seats[0]->sold;                                   
                                @endphp
                                                        
                                @if (($type === 1) && ($sold === 0))
                                    <span class="buying-scheme__chair buying-scheme__chair_standart" data-row="{{ $r }}" data-seat="{{ $s }}" data-seatnumber="{{ $counter }}" data-type=1 data-selected='' style="padding: 10px; cursor: pointer"></span>
                                @endif
                                @if (($type === 2) && ($sold === 0))
                                    <span class="buying-scheme__chair buying-scheme__chair_vip" data-row="{{ $r }}" data-seat="{{ $s }}" data-seatnumber="{{ $counter }}" data-type=2 data-selected='' style="padding: 10px; cursor: pointer"></span>
                                @endif
                                @if ($sold === 1)
                                    <span class="buying-scheme__chair buying-scheme__chair_taken" data-row="{{ $r }}" data-seat="{{ $s }}" data-seatnumber="{{ $counter }}" data-type=4 style="padding: 10px; cursor: pointer"></span>
                                @endif
                                @if ($type === 0)
                                    <span class="buying-scheme__chair buying-scheme__chair_disabled" data-row="{{ $r }}" data-seat="{{ $s }}" data-seatnumber="{{ $counter }}" data-type=0 style="padding: 10px"></span>
                                @endif
                                
                                @php
                                    $counter++;
                                @endphp
                            @endfor
                        </div>                        
                    @endfor
                </div> 
            </div>
        
            <div class="buying-scheme__legend">
                <div class="col">
                    <p class="buying-scheme__legend-price" style="color: black"><span class="buying-scheme__chair buying-scheme__chair_standart"></span> Свободно (<span class="buying-scheme__legend-value">{{ $usual_cost }}</span>руб)</p>
                    <p class="buying-scheme__legend-price" style="color: black"><span class="buying-scheme__chair buying-scheme__chair_vip"></span> Свободно VIP (<span class="buying-scheme__legend-value">{{ $vip_cost }}</span>руб)</p>            
                </div>
                <div class="col">
                    <p class="buying-scheme__legend-price" style="color: black"><span class="buying-scheme__chair buying-scheme__chair_taken"></span> Занято</p>
                    <p class="buying-scheme__legend-price" style="color: black"><span class="buying-scheme__chair buying-scheme__chair_selected"></span> Выбрано</p>                    
                </div>
            </div>

            <form action="{{ route('chooseTickets') }}" method="get" accept-charset="utf-8">
                @csrf
                <input type="hidden" name="film_name" value="{{ $film_name }}">
                <input type="hidden" name="hall_name" value="{{ $hall_name }}">
                <input type="hidden" name="film_date" value="{{ $film_date }}">
                <input type="hidden" name="session_time" value="{{ $film_start }}">
                <input type="hidden" name="total_cost" value="">
                <input type="hidden" name="arr" value="">
                <button class="acceptin-button" type="submit" style="cursor: pointer">Забронировать</button>
            </form>
        </section>  
        
        <div class="popup" id="Waiting_Background">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__wrapper">                        
                        <p class="conf-step__paragraph">Обработка данных. Жди...</p>                       
                    </div>
                </div>
            </div>
        </div>
    </main>

  @vite('resources/js/client/accordeon_hall.js')
</body>
</html>