@extends('layouts.app')

@section('title')Create @endsection

@section('content')
<h1>Создать задачу</h1>


<form action="{{route('submit')}}" method="post" style="margin-bottom: 100px">
    @csrf
    <div class="form-control-color">
        <label style="min-width: 200px" for="name">Введите название задачи</label>
        <input type="text" name="name" placeholder="введите название" id="name" class="form-control" style="min-width: 400px">
    </div>

    <div class="form-control-color" style="margin-top: 50px">
        <label style="min-width: 200px" for="name">Введите описание задачи</label>
        <textarea name="task" placeholder="введите задачу" id="task" class="form-control" style="min-width: 500px"></textarea>
    </div>

    <button type="submit" class="btn btn-success" style="margin-top: 70px">Отправить</button>
</form>
@endsection
