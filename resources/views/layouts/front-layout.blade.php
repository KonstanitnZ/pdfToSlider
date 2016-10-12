<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', ' Создание слайдера из pdf') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>   

        <link href="/css/front-style.css" rel="stylesheet">  
        @yield('head')        
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @section('header')
                @if (Route::has('login'))
                    <div class="top-right links">
                        <a href="{{ url('/login') }}">Залогироваться</a>
                        <a href="{{ url('/register') }}">Регистрация</a>
                    </div>
                @endif
            @endsection

            @yield('header')

            <div class="content">
                @yield('content')             
            </div>
        </div>

        <script src="/js/scripts.js"></script> 
        @yield('footer-scripts')
    </body>
</html>
