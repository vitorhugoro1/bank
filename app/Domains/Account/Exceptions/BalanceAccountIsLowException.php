<?php

namespace App\Domains\Account\Exceptions;

use App\Shared\Exceptions\ApiException;

class BalanceAccountIsLowException extends ApiException
{
    protected $code = 400;

    protected $message = "Account does not have enough balance.";
}
