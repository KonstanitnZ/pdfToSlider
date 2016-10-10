<?php

namespace App\Http\Controllers;

use App\Http\Requests\MakeSliderRequest;
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
            var_dump($request->pdf->extension());
        } else {
            var_dump($request->pdf->getErrorMessage());
        }
    	//$pdf = app('Pdf', ['pathToFile' => '1.pdf']);
    	return view('welcome');
    }

}
