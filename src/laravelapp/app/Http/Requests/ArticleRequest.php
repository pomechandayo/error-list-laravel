<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' => 'required' | 'max:100' ,
            'body' =>  'required'| 'max:2000',
        ];
    }

    public function messages() {
        return [
            'title.required' => 'タイトルは必須です' ,
            'title.max' => 'タイトルは100文字までです' ,
            'body.required' => '本文は必須です' ,
            'body.max' => '本文は2000文字までです',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'body' => '本文',
        ];
    }
}
