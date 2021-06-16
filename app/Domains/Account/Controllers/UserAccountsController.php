<?php

namespace App\Domains\Account\Controllers;

use App\Domains\Account\Requests\StoreAccountRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserAccountsController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->accounts;
    }

    public function store(StoreAccountRequest $request)
    {
        $account = $request->user()->accounts()->create([
            'type' => $request->get('type'),
            'balance' => $request->get('balance')
        ]);

        return response()->json(
            $account,
            201
        );
    }
}
