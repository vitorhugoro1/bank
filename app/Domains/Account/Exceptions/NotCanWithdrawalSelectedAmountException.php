<?php

namespace App\Domains\Account\Exceptions;

use App\Shared\Exceptions\ApiException;

class NotCanWithdrawalSelectedAmountException extends ApiException
{
    protected $code = 400;

    protected $message = "Not can withdrawal selected amount.";
}
