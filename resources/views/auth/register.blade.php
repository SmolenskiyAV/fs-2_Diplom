@extends('layouts.app')

@section('title')Register @endsection

@section('content')
    <h1>Регистрация пользователя</h1>


    <form action="{{route('user.register')}}" method="post" style="margin-bottom: 100px">
        @csrf
        <div class="form-control-color">
            <label style="min-width: 200px" for="email">Ваш email</label>
            <input type="text" value="" name="email" placeholder="введите адрес эл.почты" id="email" class="form-control" style="min-width: 400px">
        </div>

        <div class="form-control-color" style="margin-top: 50px">
            <label style="min-width: 200px" for="password">Пароль</label>
            <input type="password" value="" name="password" placeholder="введите пароль" id="email" class="form-control" style="min-width: 400px">
        </div>

        <button type="submit" name="sendMe" value="1" class="btn btn-lg btn-primary" style="margin-top: 70px">Зарегистрироваться</button>
    </form>
    <a class="me-3 py-2 text-2xl text-decoration-none" href="{{route('user.login')}}">Я уже зарегистрирован!</a>
@endsection
