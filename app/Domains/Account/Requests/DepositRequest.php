<?php

namespace App\Domains\Account\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepositRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amount' => ['required', 'integer', 'gt:0']
        ];
    }
}
