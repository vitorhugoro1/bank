<?php

namespace App\Domains\Account\DataTransferObject;

use Spatie\DataTransferObject\DataTransferObject;

class WithdrawalRequestData extends DataTransferObject
{
    public int $amount;
}
