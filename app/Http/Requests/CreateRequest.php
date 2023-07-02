<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'name' => 'required',
            'task' => 'required|max:500',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ' Поле "название задачи" должно быть заполнено!',
            'task.required' => ' Поле "описание задачи" должно быть заполнено!',
        ];
    }

}
