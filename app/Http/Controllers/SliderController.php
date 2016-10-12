<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Http\Requests;

class SliderController extends Controller
{
	// function for showing chosen slider
    public function showTemporalySlider($sliderPrefix) {

        $sliderRootFolder = public_path() . '/sliders/temporaly/slider_' . $sliderPrefix;
        $urlForImages = $sliderRootFolder . '/download/img/';     
    	$folderForZipping = $sliderRootFolder. '/download';

    	// have no slider? - let's talk about it
    	if (!File::exists($sliderRootFolder)) return view('slider')->with('error', 'Такого слайдера не существует');

    	$images = File::files($urlForImages);

    	if ($images) {
	        foreach ($images as $image) {
	            $fileinfo = pathinfo($image);
	            $fileNames[] = $fileinfo['basename'];
	            $imagesUrls[] = '/sliders/temporaly/slider_' . $sliderPrefix . '/download/img/' . $fileinfo['basename'];
	        };
	    };

	    // create index.html for slider
	    $html = View::make('slider.download')->with('images',$fileNames)->render();
	    File::put( public_path() . '/sliders/temporaly/slider_' . $sliderPrefix . '/download/index.html', $html );
	    // create .zip
	    $zipper = new \Chumper\Zipper\Zipper;
    	$zipper->make( $sliderRootFolder . '/slider.zip' )->add($folderForZipping);

    	return view('slider')->with(
    		[
    		'images' => $imagesUrls,
    		'sliderPrefix' => $sliderPrefix
    		]
    	);
    }

    // download slider as .zip
    public function downloadSlider($sliderPrefix) {

    	$sliderRootFolder = public_path() . '/sliders/temporaly/slider_' . $sliderPrefix;    	
    	$zip = $sliderRootFolder . '/slider.zip';
    	if (File::exists($zip)) {
    		return response()->download($zip);
    	} else return back()->with('zipError', 'Не удалось создать файл архива');
    }

    // API function return list of slider image's links
    public function getImagesApi($sliderPrefix) {
    	$urlForImages = public_path() . '/sliders/temporaly/slider_' . $sliderPrefix . '/download/img/';

    	if(!File::exists($urlForImages)) return response()->json(['error' => 'Слайдера не существует']);

    	$images = File::files($urlForImages);

    	if ($images) {
	        foreach ($images as $image) {
	            $fileinfo = pathinfo($image);
	            $imagesUrls[] = asset('/sliders/temporaly/slider_' . $sliderPrefix . '/download/img/' . $fileinfo['basename']);
	        };
	    };

	    return response()->json(['images' => $imagesUrls]);
    }

}
