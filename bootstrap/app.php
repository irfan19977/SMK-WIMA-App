<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
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
        
        // Apply GlobalSetLanguage to guest routes as well
        $middleware->group('guest', [
            \App\Http\Middleware\GlobalSetLanguage::class,
        ]);
        
        // Register Spatie Permission middleware
        $middleware->alias([
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            '/rfid-detect',
            '/clear-rfid-cache',
            '/get-latest-rfid',
            'api/rfid/*', // Exclude API RFID routes from CSRF
            '/screen-sharing/*', // Exclude WebRTC routes from CSRF
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        // Generate sitemap daily at 2 AM
        $schedule->command('sitemap:generate')->dailyAt('02:00');
        
        // For testing: every minute (comment out in production)
        // $schedule->command('sitemap:generate')->everyMinute();
    })
    ->create();
