<?php

namespace App\Domains\Account\Controllers;

use App\Http\Controllers\Controller;
use App\Domains\Account\Requests\DepositRequest;
use App\Domains\Account\Models\Account;

class DepositController extends Controller
{
    public function __invoke(Account $account, DepositRequest $request)
    {
        $this->authorize('view', $account);

        $account->balance = $account->balance + $request->get('amount');
        $account->save();

        return response()->json([
            'depositAmount' => $request->get('amont'),
            'balance' => $account->balance
        ]);
    }
}
