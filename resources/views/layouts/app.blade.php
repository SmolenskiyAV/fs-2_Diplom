<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
    <div class="container py-3">
        @include('inc.header')

        @if(Request::is('/'))
            @include('inc.hello')
        @endif

        <div class="container">
            @include('inc.massages')

            <div class="row">
                <div class="col-8">
                    @yield('content')
                </div>
                <div class="col-4">
                    @include('inc.aside')
                </div>
            </div>
        </div>

        @include('inc.footer')
    </div>
</body>
</html>
