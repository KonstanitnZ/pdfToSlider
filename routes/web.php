<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'MainPageController@index');
Route::post('/', 'MainPageController@createSlider');
Route::get('/progress/{sliderPrefix}', 'MainPageController@progressCreateSlider')->middleware('ajax');

// Show slider route
Route::get('/slider/{sliderPrefix}', 'SliderController@showTemporalySlider')->name('slider');
// download slider in .zip
Route::get('/download/{sliderPrefix}', 'SliderController@downloadSlider')->name('download');
Route:: get('/api/{sliderPrefix}', 'SliderController@getImagesApi')->middleware('ajax');;

Auth::routes();

Route::get('/home', 'HomeController@index');



