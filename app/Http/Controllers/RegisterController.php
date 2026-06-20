<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = User::create($request->validated());

        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Добро пожаловать!');
    }
}
