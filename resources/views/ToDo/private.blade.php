@extends('layouts.app')

@section('title')Private @endsection

@section('content')
    @php
        $email = Auth::user()->email // получаем адрес текущего авторизованного пользователя
    @endphp
    <h1>Личный кабинет</h1>
    <h2> Аккаунт: {{ $email }}</h2>

    <form action="{{route('user.updatePassword', $email)}}" method="post" style="margin-bottom: 100px ">
        @csrf
        <div class="form-control-color">
            <label style="min-width: 500px" for="password">Новый пароль</label>
            <input type="password" name="password" value="" placeholder="введите новый пароль" id="password" class="form-control" style="min-width: 300px">
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 70px">Изменить пароль</button>
    </form>
@endsection
