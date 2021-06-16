<?php

namespace App\Domains\Account\DataTransferObject;

use Spatie\DataTransferObject\DataTransferObject;

class WithdrawalResponseData extends DataTransferObject
{
    public int $withdrawalAmount;

    public int $balance;

    public array $notes;
}
