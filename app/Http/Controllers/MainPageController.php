<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSliderRequest;
use Illuminate\Support\Facades\Storage;
use Validator;

class MainPageController extends Controller
{

    public function index()
    {
        return view('welcome');
    }


    public function createSlider(MakeSliderRequest $request)
    {
        if ($request->pdf->isValid()) {
            // 'time' is used for showing progress in browser and make folder for slider
            $folderName = 'slider_' . $request->time;
            $sliderRootDir = 'temporaly/' . $folderName;
            $sliderImageDir = 'temporaly/' . $folderName . '/images';

            Storage::disk('sliders')->makeDirectory($sliderRootDir, 0777); 
            Storage::disk('sliders')->makeDirectory($sliderImageDir, 0777); // folder for slider images

            // inform that working in progress
            $progressFileUrl =  $sliderRootDir . '/progress.txt';
            Storage::disk('sliders')->put($progressFileUrl, 'Подготовка к обработке файла...');

            $request->pdf->storeAs('temporaly/' . $folderName, 'slider.pdf' , 'sliders');

            $url = public_path() . '/sliders/' . $sliderRootDir . '/slider.pdf';
            $urlForImages = public_path() . '/sliders/' . $sliderImageDir . '/';
            
            $pdf = app('Pdf', ['pathToFile' => $url]); // create images from pdf
            $numberOfPages = $pdf->getNumberOfPages();

            // max 20 pages
            $numberOfPages = ($numberOfPages >= 10) ? 10 : $numberOfPages;            
            // save all pages as .jpg files
            for ($i=1; $i<=$numberOfPages; $i++)  {
                // data for progress in browser
                $content = 'Обрабатывается ' . $i . ' страница из ' . $numberOfPages;
                Storage::disk('sliders')->put($progressFileUrl, $content);

               $pdf->setPage($i)
                    ->saveImage($urlForImages . $i . '.jpg');
            }

        }
    	
    	return view('welcome');
    }

    // get infomation about progress for browser (AJAX request required)
    public function progressCreateSlider($sliderPrefix) {
        $content = Storage::disk('sliders')->get('temporaly/slider_' . $sliderPrefix . '/progress.txt');
        if ($content) {
            return response()->json(
                [
                    'stage' => $content,
                ]
            );
        };

    }
}
