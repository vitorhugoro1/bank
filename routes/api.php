<?php

use App\Domains\Account\Controllers\DepositController;
use App\Domains\Account\Controllers\UserAccountsController;
use App\Domains\Account\Controllers\WithdrawalController;
use App\Domains\Users\Controllers\SignInController;
use Illuminate\Support\Facades\Route;
use App\Domains\Users\Controllers\LoginController;
use App\Domains\Users\Controllers\MeController;
use App\Domains\Reports\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['prefix' => 'user', 'as' => 'user.'], function () {
        Route::get('/', MeController::class)->name('me');

        Route::resource('accounts', UserAccountsController::class, ['except' => ['create', 'edit']]);

        Route::post('accounts/{account}/withdrawal', WithdrawalController::class)->name('accounts.withdrawal');
        Route::post('accounts/{account}/deposit', DepositController::class)->name('accounts.deposit');

        Route::resource('accounts.reports', ReportController::class, ['only' => ['index']]);
    });
});

Route::post('auth/token', LoginController::class)->name('login');
Route::post('auth/create', SignInController::class)->name('signin');
