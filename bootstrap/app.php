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
            \App\Http\Middleware\TrackVisitors::class,
            \App\Http\Middleware\GlobalSetLanguage::class,
        ]);
        
        $middleware->group('frontend', [
            \App\Http\Middleware\FrontendLanguageSync::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/rfid-detect',
            '/clear-rfid-cache',
            '/get-latest-rfid',
            '/screen-sharing/*', // Exclude WebRTC routes from CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
