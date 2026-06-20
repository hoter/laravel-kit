<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class ThrottleByRole
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$params): Response
    {
        $decayMinutes = 1;
        $roleParams = $params;

        if (!empty($params) && is_numeric($params[0])) {
            $decayMinutes = max(1, (int) $params[0]);
            $roleParams = array_slice($params, 1);
        }

        $limits = $this->parseLimits($roleParams);

        $role = $request->user()?->role ?? 'guest';

        $maxAttempts = $limits[$role]
            ?? $limits['guest']
            ?? (!empty($limits) ? min($limits) : 10);

        $key = 'role-throttle|' . $role . '|' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            return $this->buildThrottleResponse($key, $maxAttempts, $decayMinutes);
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $this->addHeaders(
            $response,
            $maxAttempts,
            $maxAttempts - RateLimiter::attempts($key),
        );
    }

    /**
     * @param  string[]  $params  Alternating role, limit pairs.
     * @return array<string, int>
     */
    private function parseLimits(array $params): array
    {
        $limits = [];

        for ($i = 0; $i < count($params); $i += 2) {
            if (isset($params[$i + 1]) && is_numeric($params[$i + 1])) {
                $limits[$params[$i]] = (int) $params[$i + 1];
            }
        }

        return $limits;
    }

    private function buildThrottleResponse(string $key, int $maxAttempts, int $decayMinutes): Response
    {
        $retryAfter = RateLimiter::availableIn($key);

        $response = response()->make(
            __('auth.throttle', ['seconds' => $retryAfter]),
            429,
        );

        return $this->addHeaders($response, $maxAttempts, 0)
            ->withHeaders(['Retry-After' => $retryAfter]);
    }

    private function addHeaders(Response $response, int $limit, int $remaining): Response
    {
        $response->headers->set('X-RateLimit-Limit', (string) $limit);
        $response->headers->set('X-RateLimit-Remaining', (string) max(0, $remaining));

        return $response;
    }
}
