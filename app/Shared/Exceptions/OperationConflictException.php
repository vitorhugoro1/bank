<?php

namespace App\Shared\Exceptions;

class OperationConflictException extends ApiException
{
    protected $code = 409;

    protected $message = "Account has operation on execution.";
}
