<?php

use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\CheckUserRoleGlobal;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Router;
use Illuminate\Foundation\Configuration\RouterMiddleware;
use Illuminate\Foundation\Configuration\RouterMiddlewareGroup;
use App\Http\Middleware\CheckClientAccess;



return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->appendToGroup('role', CheckUserRole::class);
        $middleware->appendToGroup('roleGlobal', CheckUserRoleGlobal::class);
        $middleware->appendToGroup('NoAccess', CheckClientAccess::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
