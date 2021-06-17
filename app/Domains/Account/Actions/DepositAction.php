<?php

namespace App\Domains\Account\Actions;

use App\Domains\Account\Models\Account;
use App\Domains\Reports\Actions\IssueOperation;
use App\Domains\Reports\Enums\ReportOperationEnum;

class DepositAction
{
    public function __construct(private IssueOperation $issueOperation)
    {
        //
    }

    public function execute(Account $account, int $amount): float
    {
        $account->balance = $account->balance + $amount;
        $account->save();

        $this->issueOperation->onQueue()->execute(
            $account,
            ReportOperationEnum::deposit(),
            $amount,
            $account->balance,
            now()
        );

        return $account->balance;
    }
}
