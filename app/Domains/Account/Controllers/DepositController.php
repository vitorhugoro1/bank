<?php

namespace App\Domains\Account\Controllers;

use App\Domains\Account\Actions\DepositAction;
use App\Http\Controllers\Controller;
use App\Domains\Account\Requests\DepositRequest;
use App\Domains\Account\Models\Account;

class DepositController extends Controller
{
    public function __invoke(Account $account, DepositRequest $request, DepositAction $depositAction)
    {
        $this->authorize('view', $account);

        return response()->json([
            'depositAmount' => $request->get('amount'),
            'balance' => $depositAction->execute(
                account: $account,
                amount: $request->get('amount')
            )
        ]);
    }
}
