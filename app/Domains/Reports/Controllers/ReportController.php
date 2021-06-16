<?php

namespace App\Domains\Reports\Controllers;

use App\Domains\Account\Models\Account;

class ReportController
{
    public function index(Account $account)
    {
        return response()->json(
            $account->report
        );
    }
}
