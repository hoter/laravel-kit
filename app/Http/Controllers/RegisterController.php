<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Facades\Currency;
use Illuminate\Support\Facades\Cache;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request) {
        $user = User::create($request->validated());

        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Добро пожаловать!');
    }

    public function currency()
    {
        $usdToRub = Cache::get('usdToRub',Currency::getRate('USD', 'RUB'));
        $rubAmount = Currency::convert(100, 'USD', 'RUB');
        $rates = Currency::getAllRates('EUR');
        $currency = Currency::convert(100, 'USD', 'RUB');

        return "usdToRub: $usdToRub, rubAmount: $rubAmount, currency: $currency";
    }
}
