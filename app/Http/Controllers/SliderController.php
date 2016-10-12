<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use App\Http\Requests;

class SliderController extends Controller
{
    public function showTemporalySlider($sliderPrefix) {

        $urlForImages = public_path() . '/sliders/temporaly/slider_' . $sliderPrefix . '/download/img/';
        $sliderRootFolder = public_path() . '/sliders/temporaly/slider_' . $sliderPrefix;
    	$folderForZipping = $sliderRootFolder. '/download';

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

    public function getImagesApi($sliderPrefix) {
    	$urlForImages = public_path() . '/sliders/temporaly/slider_' . $sliderPrefix . '/download/img/';

    	$images = File::files($urlForImages);

    	if ($images) {
	        foreach ($images as $image) {
	            $fileinfo = pathinfo($image);
	            $fileNames[] = $fileinfo['basename'];
	            $imagesUrls[] = asset('/sliders/temporaly/slider_' . $sliderPrefix . '/download/img/' . $fileinfo['basename']);
	        };
	    };

	    return response()->json(['images' => $imagesUrls]);
    }

}
