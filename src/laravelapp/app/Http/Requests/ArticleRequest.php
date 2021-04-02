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
            'title' => 'required | max:100' ,
            'tags' => 'required',
            'body' =>  'required',
        ];
    }

    public function messages() {
        return [
            'title.required' => 'タイトルは必須です' ,
            'title.max' => 'タイトルは100文字までです' ,
            'tags' => 'タグは必須です',
            'body.required' => '本文は必須です' ,
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'タイトル',
            'tags' => 'タグ',
            'body' => '本文',
        ];
    }
}
