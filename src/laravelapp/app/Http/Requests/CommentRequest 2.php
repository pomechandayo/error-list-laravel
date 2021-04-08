<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'body' =>  'required',
        ];
    }
     public function messages() 
     {
          return ['body.required' => 'コメントを記入してください' ,];
     }
     public function attributes()
     {
         return [
             'body' => '本文',
         ];
     }
     
}
