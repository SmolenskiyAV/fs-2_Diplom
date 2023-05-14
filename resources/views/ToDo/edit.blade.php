@extends('layouts.app')

@section('title')Edit @endsection

@section('content')
    <h1>{{ $data->name }}</h1>
        <div class="alert alert-info">
            <p>{{ $data->task }}</p>
            <p><small>{{ $data->created_at }}</small></p>
            <a href="{{ route('update', $data->id) }}"><button class="btn btn-primary">Редактировать</button> </a>
            <a href="{{ route('delete', $data->id) }}"><button class="btn btn-danger">Удалить</button> </a>
        </div>
@endsection

