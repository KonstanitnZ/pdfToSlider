<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MakeSliderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'pdf'=>'required|mimes:pdf|max:10240'
        ];
    }

    public function messages () {
        return [
            'pdf.required'=>'Вы забыли выбрать файл',
            'pdf.mimes'=>'Ваш файл должен быть pdf',
            'pdf.max'=>'Размер файла должен быть не более 10 Мб',
        ];
    }
}
