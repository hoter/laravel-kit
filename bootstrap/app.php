<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\CheckAdmin;
use App\Http\Middleware\CheckPermission;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\MaintenanceMode;
use App\Http\Middleware\ThrottleByRole;
use App\Http\Middleware\TrackUserActivity;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin'           => CheckAdmin::class,
            'maintenance'     => MaintenanceMode::class,
            'permission'      => CheckPermission::class,
            'role'            => CheckRole::class,
            'throttleByRole'  => ThrottleByRole::class,
            'track.activity'  => TrackUserActivity::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
