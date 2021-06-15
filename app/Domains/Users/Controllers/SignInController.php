<?php

namespace App\Domains\Users\Controllers;

use App\Domains\Users\Requests\SignInRequest;
use App\Domains\Users\Models\User;

class SignInController
{
    public function __invoke(SignInRequest $request)
    {
        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'document' => $request->get('document'),
            'birthday' => $request->get('birthday'),
            'password' => bcrypt($request->get('password'))
        ]);

        return response()->json([
            'token' => $user->createToken('api')->plainTextToken
        ], 201);
    }
}
