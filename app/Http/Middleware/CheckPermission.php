<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Проверяем, авторизован ли пользователь
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему.');
        }

        // Проверяем роль
        $hasAccess = match(auth()->user()->role) {
            'editor' => in_array($permission, ['view-posts', 'create-posts', 'edit-posts', 'publish-posts']),
            'user' => in_array($permission, ['view-posts', 'create-comments']),
            default => in_array($permission, ['view-posts'])
        };
        if (!$hasAccess) {
            abort(403, 'У вас нет необходимых прав.');
        }

        return $next($request);
    }
}
