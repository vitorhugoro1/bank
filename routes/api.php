<?php

use App\Domains\Users\Controllers\SignInController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Domains\Users\Controllers\LoginController;
use App\Domains\Users\Controllers\MeController;

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
    Route::get('user', MeController::class)->name('me');
});

Route::post('auth/token', LoginController::class)->name('login');
Route::post('auth/create', SignInController::class)->name('signin');
