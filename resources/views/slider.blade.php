@extends('layouts.front-layout')
@section('head')
    <link href="{{ asset('js/bxslider/jquery.bxslider.min.css') }}" rel="stylesheet" />
    <style>
        .slider_container {
            max-width: 1000px;
            margin: 0 auto;
        }
    </style>
@endsection

@section('content')
    @if (!empty($zipError))
        <p class="error">{{ $zipError }}</p>
    @endif
    @if (!empty($error))
        <p class="error">{{ $error }}</p>
        <p><a href="/">Перейти на главную</a></p>
    @else
        <div class="slider_container">
            <ul class="bxslider">
                @foreach($images as $image)
                    <li><img src="{{ asset($image) }}"/></li>
                @endforeach
            </ul>
        </div>
        <div class='downloadAndTestApiButtons'>
            <a href="/download/{{ $sliderPrefix }}">Скачать</a>
            <a href="/api/{{ $sliderPrefix }}" id='testApi'>Протестировать API</a>
        </div>
        <div id="apiAnswer">
        <p>Ссылка API для получения кратинок (требуется AJAX): {{ action('SliderController@getImagesApi', ['sliderPrefix' => $sliderPrefix]) }}</p>
        </div>
    @endif
@endsection

@section('footer-scripts')
    <!-- bxSlider Javascript file -->
    <script src="{{ asset('js/bxslider/jquery.bxslider.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('.bxslider').bxSlider({
                adaptiveHeight : true,
            });

            $('#testApi').click(function(e) {
                e.preventDefault();
                var hrefList = $('<div/>', { 'class':'hrefList' });
                var imagesList = $('<div/>', { 'class':'imagesList' });
                var answerBlock = $('#apiAnswer');
                var href = $(this).attr('href');
                $.ajax({
                    url: href,
                    method: "GET",

                    success: function(data){
                        $(answerBlock).find('.hrefList').remove();
                        $(answerBlock).find('.imagesList').remove();
                        var imagesCount = data.images.length;
                        for(var i=0; i<imagesCount; i++) {
                            $(hrefList).append("<a href='"+ data.images[i] + "' target='_blank'>" + data.images[i] + "</a>" );
                            $(imagesList).append("<img src='"+ data.images[i] + "'/>");
                        }
                        $(answerBlock).append(hrefList);
                        $(answerBlock).append(imagesList);
                        $('html, body').animate({
                            scrollTop: $(answerBlock).offset().top
                        }, 1000);
                    },
                });
            });
        });
    </script>
@endsection


