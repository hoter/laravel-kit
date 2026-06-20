<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMode
{
    /**
     * @param  string  ...$exceptRoles  Роли, которым разрешён доступ
     */
    public function handle(Request $request, Closure $next, string ...$exceptRoles): Response
    {
        $role = $request->user()?->role;

        if ($role && in_array($role, $exceptRoles, true)) {
            return $next($request);
        }

        return response('Сайт на обслуживании.', 503);
    }
}
