@extends('layouts.front-layout')

@section('content')
    @if (count($errors) > 0)
        @foreach($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    @endif
    <div class="title m-b-md">
        Создание слайдера из pdf
    </div>
    <form method="POST" action="{{ url('/') }}" enctype="multipart/form-data">
        <input type='hidden' name='_token'value='{{ csrf_token() }}'/>
        <label>Загрузить pdf</label>
        <a id="upload_trigger">Выбрать файл</a>
        <input type="file" name="pdf" style="display:none;"/>
        <button type="submit">Отправить</button>
    </form>
    <p class="progress" style="display:none;"><img src="{{ url('/images/ajax-loader.gif') }}"/><span></span></p>
@endsection

@section('footer-scripts')
    <script>
        $(function() {
            /* add point for making slider folder */
            var sliderPrefix;
            $('form').submit(function(e){
                sliderPrefix = new Date().getTime();
                $(this).append("<input type='hidden' name='time' value='" + sliderPrefix +"' />");
                setInterval('getProgress(' + sliderPrefix + ')', 5000);
                $('p.progress').show();
                return;
            });
        });

        function getProgress(sliderPrefix){            
            $.ajax({
                url: "progress/" + sliderPrefix,
                method: "GET",

                success: function(data){
                    $('p.progress').find('span').text(data.stage);
                },
            });
        }
    </script>
@endsection


