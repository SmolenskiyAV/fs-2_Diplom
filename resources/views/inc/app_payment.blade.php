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
	$seats_list = '';
	$count = 0;			
	$arr_size = count($choose_array);			
	$film_name = '';
@endphp

<body>
  <header class="page-header">
  <a href="{{route('client_main')}}" style="text-decoration: none"><h1 class="page-header__title">Идём<span>в</span>кино</h1></a>
  </header>
  
  <main>
    <section class="ticket">
      
      <header class="tichet__check">
        <h2 class="ticket__check-title">Вы выбрали билеты:</h2>
      </header>
      
      <div class="ticket__info-wrapper">
        <p class="ticket__info">На фильм: <span class="ticket__details ticket__title">{{ $film_name }}</span></p>
			
		@php			
			if($arr_size === 1) {
				$seats_list = $choose_array[0]->seatnumber;
			} else {
			
				foreach ($choose_array as $el) {
					$count++;
					if($count == $arr_size) {
						$seats_list = $seats_list . $el->seatnumber;						
					} else {
						$seats_list = $seats_list . $el->seatnumber . ', ';
					}
				}
			}
		@endphp

        <p class="ticket__info">Места: <span class="ticket__details ticket__chairs">{{ $seats_list }}</span></p>		
        <p class="ticket__info">В зале: <span class="ticket__details ticket__hall">{{ $hall_name }}</span></p>
        <p class="ticket__info">Начало сеанса: <span class="ticket__details ticket__start">{{ $session_time }}</span></p>
        <p class="ticket__info">Стоимость: <span class="ticket__details ticket__cost">{{ $total_cost }}</span> рублей</p>

        <form action="{{ route('getTicketCode') }}" method="post" accept-charset="utf-8">
            @csrf
                <input type="hidden" name="film_name" value="{{ $film_name }}">
                <input type="hidden" name="hall_name" value="{{ $hall_name }}">
                <input type="hidden" name="film_date" value="{{ $film_date }}">
                <input type="hidden" name="session_time" value="{{ $session_time }}">
				<input type="hidden" name="seats_list" value="{{ $seats_list }}">
                <input type="hidden" name="total_cost" value="{{ $total_cost }}">
				<input type="hidden" name="arr" value="{{ json_encode($choose_array) }}">
			@if(count($choose_array) > 0)
            	<button class="acceptin-button" type="submit" style="cursor: pointer">Получить код бронирования</button>
			@endif
		</form>

        <p class="ticket__hint">После оплаты билет будет доступен в этом окне, а также НЕ придёт вам на почту. Покажите QR-код нашему контроллёру у входа в зал.</p>
        <p class="ticket__hint">Приятного просмотра!</p>
      </div>
    </section>     
  </main>

  @vite('resources/js/client/accordeon_payment.js')
</body>
</html>