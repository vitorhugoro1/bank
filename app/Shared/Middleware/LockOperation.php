<?php

namespace App\Shared\Middleware;

use App\Domains\Account\Models\Account;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Shared\Exceptions\OperationConflictException;

/**
 * Bloqueia uma próxima operação da conta até ela finalizar a operação atual
 * Se a operação durar mais de 10 segundos ele remove o lock para não bloquear a conta do usuário
 */
class LockOperation
{
    private const LOCK_SECONDS = 10;

    public function handle(Request $request, Closure $next)
    {
        if (!$request->route('account')) {
            return $next($request);
        }

        $account = $request->route('account');

        if (!$request->route('account') instanceof Account) {
            $account = Account::findOrFail($request->route('account'));
        }

        throw_if(Cache::get("{$account->id}_operation"), OperationConflictException::class);

        Cache::put(
            "{$account->id}_operation",
            now()->addSeconds(self::LOCK_SECONDS)->timestamp,
            self::LOCK_SECONDS
        );

        $response = $next($request);

        Cache::forget("{$account->id}_operation");

        return $response;
    }
}
