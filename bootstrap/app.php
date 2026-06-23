<?php

use App\Http\Middleware\EnsureEmailIsVerified;
use App\Http\Middleware\EnsureHasE5Code;
use App\Http\Middleware\EnsureHasPermission;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // The SPA authenticates against the API via Sanctum cookie auth.
        $middleware->statefulApi();

        // The api group lost throttle:api in Laravel 11 — re-add it (limiter
        // is defined in AppServiceProvider as 60/min per user or IP).
        $middleware->throttleApi();

        // Running behind AWS Lambda / API Gateway / ELB: trust every proxy and
        // honour the ELB forwarding header so scheme & client IP resolve correctly.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB);

        // CSRF is only disabled for the API and the legacy OAuth popup callback.
        // Web routes (including Livewire) retain CSRF protection.
        $middleware->validateCsrfTokens(except: ['api/*', 'auth/callback']);

        // Unauthenticated users hitting the "auth" middleware go to the login page.
        $middleware->redirectGuestsTo('/login');

        // Middleware aliases used in route definitions.
        $middleware->alias([
            'verified'   => EnsureEmailIsVerified::class,
            'has.e5code' => EnsureHasE5Code::class,
            'permission' => EnsureHasPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// Allow overriding the storage path (e.g. Lambda's writable /tmp) via env.
$app->useStoragePath(env('APP_STORAGE', $app->basePath('storage')));

return $app;
