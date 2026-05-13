<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'sales' => \App\Http\Middleware\EnsureSales::class,
            'guest.auth' => \App\Http\Middleware\EnsureGuestLogin::class,
        ]);
        
        $middleware->redirectGuestsTo(function () {
            $path = trim((string) request()->path(), '/');

            if ($path === 'admin' || str_starts_with($path, 'admin/')) {
                return route('admin.login');
            }

            return route('guest.login');
        });

        // We remove global redirectUsersTo because it forces redirection based on ANY active guard.
        // This causes issues when multiple guards are active (e.g., Admin + Customer).
        // Instead, each login controller handles its own redirection, and middleware handles access control.
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->reportable(function (Throwable $e) {
            Log::error('Global Exception Handler: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'url' => request()->fullUrl(),
                'input' => request()->all(),
            ]);
        });
    })->create();
