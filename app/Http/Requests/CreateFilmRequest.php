<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateFilmRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'poster' => 'required|image|mimes:jpg,png,bmp,jpeg,svg',
            'film_name' => 'required|unique:films',      
        ];
    }

    public function messages()
    {
        session()->flash('film_msg', true);
        
        return [
            'poster.mimes' => ' Файл-постер должен быть строго определённого типа: jpg, png, jpeg, svg!',
            'poster.image' => ' Файл-постер должен быть изображением!',
            'film_name.unique' => ' Фильм с таким именем уже есть в базе данных.'
        ];
    }
}
