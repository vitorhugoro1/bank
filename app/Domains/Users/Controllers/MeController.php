<?php

namespace App\Domains\Users\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MeController extends Controller
{
    public function __invoke(Request $request)
    {
        return $request->user();
    }
}
