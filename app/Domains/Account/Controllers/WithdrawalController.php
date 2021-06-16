<?php

namespace App\Domains\Account\Controllers;

use App\Domains\Account\Actions\WithdrawalAction;
use App\Domains\Account\DataTransferObject\WithdrawalRequestData;
use App\Http\Controllers\Controller;
use App\Domains\Account\Models\Account;
use App\Domains\Account\Requests\WithdrawalRequest;

class WithdrawalController extends Controller
{
    public function __invoke(Account $account, WithdrawalRequest $request, WithdrawalAction $withdrawalAction)
    {
        $this->authorize('view', $account);

        $withdrawal = $withdrawalAction->execute(
            $account,
            new WithdrawalRequestData($request->validated())
        );

        return response()->json($withdrawal);
    }
}
