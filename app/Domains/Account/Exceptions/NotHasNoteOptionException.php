<?php

namespace App\Domains\Account\Exceptions;

use App\Shared\Exceptions\ApiException;

class NotHasNoteOptionException extends ApiException
{
    protected $code = 400;

    protected $message = 'Not has note option for selected value.';
}
