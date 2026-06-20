<?php

namespace App\Http\Middleware;

use App\Models\UserActivity;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            $user->forceFill(['last_activity_at' => Carbon::now()])->save();
        }

        UserActivity::create([
            'user_id' => $request->user()?->id,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => Carbon::now(),
        ]);

        return $next($request);
    }
}
