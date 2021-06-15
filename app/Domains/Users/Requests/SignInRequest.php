<?php

namespace App\Domains\Users\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'name' => ['required'],
            'birthday' => ['required', 'date'],
            'document' => ['required'], // @todo validação de documento
            'password' => ['required']
        ];
    }
}
