@extends('layouts.app')

@section('title')ToDo @endsection

@section('content')
<h1>Все задачи</h1>
    @foreach($data as $el)
        <div class="alert alert-info">
            <h3>{{ $el->name }}</h3>
            <p><small>{{ $el->created_at }}</small></p>
            <a href="{{ route('edit', $el->id) }}"><button class="btn btn-warning">Детали</button> </a>
        </div>
    @endforeach
@endsection

