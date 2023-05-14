@extends('layouts.app')

@section('title')Update @endsection

@section('content')
    <h1>Обновить задачу</h1>


    <form action="{{route('updateSubmit', $data->id)}}" method="post" style="margin-bottom: 100px">
        @csrf
        <div class="form-control-color">
            <label style="min-width: 200px" for="name">Введите название задачи</label>
            <input type="text" name="name" value="{{$data->name}}" placeholder="введите название" id="name" class="form-control" style="min-width: 400px">
        </div>

        <div class="form-control-color" style="margin-top: 50px">
            <label style="min-width: 200px" for="name">Введите описание задачи</label>
            <textarea name="task" placeholder="введите задачу" id="task" class="form-control" style="min-width: 500px">{{$data->task}}</textarea>
        </div>

        <button type="submit" class="btn btn-success" style="margin-top: 70px">Обновить</button>
    </form>
@endsection
