<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateHallRequest extends FormRequest
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
            'hall_name' => 'required|unique:halls',
            'hall_name' => 'regex:/[a-z0-9-\.]/i',
        ];
    }

    public function messages()
    {
        return [
            'hall_name.unique' => ' Такое имя зала уже существует! Введите другое имя зала.',
            'hall_name.regex' => ' В имени зала присутствуеут как минимум один символ, не являющийся латинской буквой, цифрой, точкой или тире!',          
        ];
    }

}
