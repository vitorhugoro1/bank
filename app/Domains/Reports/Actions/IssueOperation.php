<?php

namespace App\Domains\Reports\Actions;

use App\Domains\Account\Models\Account;
use App\Domains\Reports\Enums\ReportOperationEnum;
use App\Domains\Reports\Models\Report;
use Spatie\QueueableAction\QueueableAction;

class IssueOperation
{
    use QueueableAction;

    public function execute(Account $account, ReportOperationEnum $operation, int $amount, int $balance): Report
    {
        return Report::create([
            'account_id' => $account->id,
            'operation' => $operation,
            'amount' => $amount,
            'balance' => $balance,
            'occurred_at' => now()
        ]);
    }
}
