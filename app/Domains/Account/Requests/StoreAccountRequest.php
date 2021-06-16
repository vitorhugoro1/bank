<?php

namespace App\Domains\Account\Requests;

use App\Domains\Account\Enums\AccountType;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Enum\Laravel\Rules\EnumRule;

class StoreAccountRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => new EnumRule(AccountType::class),
            'balance' => ['required', 'integer']
        ];
    }
}
