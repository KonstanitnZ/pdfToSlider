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
            $folderName = 'slider_' . $request->sliderPrefix;
            $sliderRootDir = 'temporaly/' . $folderName;
            $urlForPdf = public_path() . '/sliders/' . $sliderRootDir . '/slider.pdf';
            $urlForImages = public_path() . '/sliders/temporaly/' . $folderName . '/download/img/';

            Storage::disk('sliders')->makeDirectory($sliderRootDir, 0777); 
            
            // copy slider repo for downloading
            File::copyDirectory(public_path() . '/sliders/slider_repo', public_path() . '/sliders/' . $sliderRootDir . '/download');

            // inform that working in progress
            $progressFileUrl =  $sliderRootDir . '/progress.txt';
            Storage::disk('sliders')->put($progressFileUrl, 'Подготовка к обработке файла...');

            // save original pdf file
            $request->pdf->storeAs('temporaly/' . $folderName, 'slider.pdf' , 'sliders');
           
            $pdf = app('Pdf', ['pathToFile' => $urlForPdf]); // create images from pdf
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
