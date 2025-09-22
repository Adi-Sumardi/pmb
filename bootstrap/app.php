<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            // Register auth routes
            Route::middleware('web')
                ->group(base_path('routes/auth.php'));

            // Register admin routes
            Route::middleware('web')
                ->prefix('admin')
                ->name('admin.')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
        $middleware->web(append: [
            \App\Http\Middleware\CloudflareMiddleware::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\SessionTimeoutMiddleware::class,
            \App\Http\Middleware\ComprehensiveSecurityMiddleware::class,
        ]);

        $middleware->api(append: [
            \App\Http\Middleware\CloudflareMiddleware::class,
            \App\Http\Middleware\ApiRateLimitMiddleware::class,
            \App\Http\Middleware\ApiSecurityMiddleware::class,
            \App\Http\Middleware\SecurityHeadersMiddleware::class,
            \App\Http\Middleware\ComprehensiveSecurityMiddleware::class,
        ]);

        // Register middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'user.role' => \App\Http\Middleware\UserMiddleware::class,
            'api.rate.limit' => \App\Http\Middleware\ApiRateLimitMiddleware::class,
            'api.security' => \App\Http\Middleware\ApiSecurityMiddleware::class,
            'session.timeout' => \App\Http\Middleware\SessionTimeoutMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeadersMiddleware::class,
            'cloudflare' => \App\Http\Middleware\CloudflareMiddleware::class,
            'secure.webhook' => \App\Http\Middleware\SecureWebhookMiddleware::class,
            'comprehensive.security' => \App\Http\Middleware\ComprehensiveSecurityMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
