@extends('layouts.app')

@section('title')Главная страница@endsection

@section('content')
<h1>Задание: Авторизация</h1>
    <p>Создать защищенный авторизацией контроллер, который будет
        отображать: **имя**, **email** и **id** авторизованного пользователя.
    </p>
@endsection

@section('aside')
    @parent
    <p>Дополнительный текст</p>
@endsection
