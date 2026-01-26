<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('showLoginAdmin');
            }
            return route('showLogin');
        });

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::user()->role === 'admin') {
                return route('dashboardAdmin'); 
            }
            return url('/dashboard'); 
        });

        // $middleware->validateCsrfTokens(except: [
        //     'payment/callback', 
        // ]); // Xendit

         $middleware->validateCsrfTokens(except: [
            'payment/callback', // Route Webhook PayLabs
        ]);

        $middleware->alias([
            'admin' => AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
