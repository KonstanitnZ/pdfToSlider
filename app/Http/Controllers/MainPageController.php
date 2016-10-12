<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSliderRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MainPageController extends Controller
{

    public function index()
    {
        return view('welcome');
    }


    public function createSlider(MakeSliderRequest $request)
    {
        if ($request->pdf->isValid()) {
            // 'sliderPrefix' is used for showing progress in browser and make folder for slider
            $prefix = $request->sliderPrefix;
            $folderName = 'slider_' . $prefix;
            $sliderRootDir = 'temporaly/' . $folderName;
            $urlForPdf = public_path() . '/sliders/' . $sliderRootDir . '/slider.pdf';
            $urlForImages = public_path() . '/sliders/' . $sliderRootDir . '/download/img/';

            // make folder for slider
            Storage::disk('sliders')->makeDirectory($sliderRootDir, 0777); 
            
            // copy empty slider for downloading
            File::copyDirectory(public_path() . '/sliders/slider_repo', public_path() . '/sliders/' . $sliderRootDir . '/download');

            // inform that working in progress
            $progressFileUrl =  $sliderRootDir . '/progress.txt';
            Storage::disk('sliders')->put($progressFileUrl, 'Подготовка к обработке файла...');

            // save original pdf file
            $request->pdf->storeAs('temporaly/' . $folderName, 'slider.pdf' , 'sliders');
            
            // create images from pdf
            $pdf = app('Pdf', ['pathToFile' => $urlForPdf]);
            $numberOfPages = $pdf->getNumberOfPages();

            // max 10 pages
            $numberOfPages = ($numberOfPages >= 10) ? 10 : $numberOfPages; 

            // save pages as .jpg files
            for ($i=1; $i<=$numberOfPages; $i++)  {

                // progress information for browser
                $content = 'Обрабатывается ' . $i . ' страница из ' . $numberOfPages;
                Storage::disk('sliders')->put($progressFileUrl, $content);

                $pdf->setPage($i)
                    ->saveImage($urlForImages . $i . '.jpg');
            };

            // go to see slider
            return redirect()->route('slider', ['sliderPrefix' => $prefix]);

        }
    	
        
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
