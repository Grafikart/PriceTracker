<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;

class AuthController
{
    public function login()
    {
        return view('auth.login');
    }

    public function doLogin(LoginRequest $request)
    {
        if (\Auth::attempt($request->validated())) {
            return to_route('prices.index');
        }

        return to_route('login')
            ->withInput()
            ->withErrors([
                'email' => __('Invalid login or password'),
            ]);
    }
}
