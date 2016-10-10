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
@endsection



