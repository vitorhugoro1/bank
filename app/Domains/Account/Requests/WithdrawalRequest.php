<?php

namespace App\Domains\Account\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawalRequest extends FormRequest
{
    public function rules()
    {
        return [
            'amount' => ['required', 'integer', 'gt:0'],
        ];
    }
}
