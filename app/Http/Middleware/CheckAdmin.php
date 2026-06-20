<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему.');
        }

        // Проверяем роль администратора
        if (auth()->user()->role !== 'admin') {
            abort(403, 'У вас нет прав администратора.');
        }

        return $next($request);
    }
}
