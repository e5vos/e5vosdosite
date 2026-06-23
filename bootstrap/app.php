<?php

use App\Http\Middleware\EnsureEmailIsVerified;
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

        // CSRF is disabled application-wide (stateless API + external auth flow).
        // Sanctum's stateful CSRF check is likewise disabled via config/sanctum.php.
        $middleware->validateCsrfTokens(except: ['*']);

        // Unauthenticated users hitting the "auth" middleware go to the login endpoint.
        $middleware->redirectGuestsTo('https://e5vosdo.hu/api/login');

        // Custom "verified" middleware responds with JSON 409 instead of a redirect.
        $middleware->alias([
            'verified' => EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// Allow overriding the storage path (e.g. Lambda's writable /tmp) via env.
$app->useStoragePath(env('APP_STORAGE', $app->basePath('storage')));

return $app;
