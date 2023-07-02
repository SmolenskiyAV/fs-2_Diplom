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
     
    <section class="conf-step" id="Halls_Control">
        <header class="conf-step__header conf-step__header_opened">
            <h2 class="conf-step__title">Вход администратора</h2>
        </header>
        <div class="conf-step__wrapper">
                        
            <div class="popup__wrapper">
                <form action="{{route('user.login')}}" method="post" accept-charset="utf-8">
                    @csrf
                    <label class="conf-step__label conf-step__label-fullsize" for="email">
                        Ваш логин
                        <input class="conf-step__inputв" type="text" id="email" placeholder="введите имя учётной записи" name="email" required>
                    </label>

                    <label class="conf-step__label conf-step__label-fullsize" for="password">
                        Ваш пароль
                        <input class="conf-step__inputв" type="text" id="password" placeholder="введите пароль" name="password" required>
                    </label>

                    <label>                                
                        <input class="conf-step__inputв" type="checkbox" id="remember" name="remember" value="remember-me"> Запомнить меня
                    </label>

                    <div class="conf-step__buttons text-center">
                        <button type="submit" name="sendMe" value="1" class="conf-step__button conf-step__button-accent">Войти</button>
                                 
                    </div>
                </form>
                <a href="{{route('admin_register')}}" style="text-decoration: none"><button class="conf-step__button conf-step__button-regular" name="redirect">Регистрация</button></a>
            </div>            
        </div>

        @if(session()->missing('film_msg'))
            @include('inc.massages')
        @endif        
     
        <div class="popup" id="Waiting_Background">
            <div class="popup__container">
                <div class="popup__content">
                    <div class="popup__wrapper">                        
                        <p class="conf-step__paragraph">Передача данных на сервер. Жди...</p>                       
                    </div>
                </div>
            </div>
        </div>
    </section>   
</main>


@vite('resources/js/admin/accordeon_login.js')
</body>
</html>
