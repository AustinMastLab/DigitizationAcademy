<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \RealRashid\SweetAlert\ToSweetAlert::class,
            \Spatie\ResponseCache\Middlewares\CacheResponse::class,
        ]);

        $middleware->alias([
            'cacheResponse' => \Spatie\ResponseCache\Middlewares\CacheResponse::class,
            'doNotCacheResponse' => \Spatie\ResponseCache\Middlewares\DoNotCacheResponse::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/opcache/reset',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
