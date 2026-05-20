<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use App\Http\Middleware\RoleMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // ->withMiddleware(function ($middleware) {
    //     $middleware->redirectGuestsTo(fn() => null);
    // })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->shouldRenderJsonWhen(
            fn($request) => $request->is('api/*') || $request->expectsJson()
        );

        $exceptions->render(function (ValidationException $e, $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            $messages = collect($e->errors())
                ->flatten()
                ->filter()
                ->values();

            return response()->json([
                'detail' => $messages->first() ?? 'Validasi gagal',
            ], $e->status);
        });

        $exceptions->render(function (AuthenticationException $e, $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'detail' => 'Unauthorized',
            ], 401);
        });

        $exceptions->render(function (HttpExceptionInterface $e, $request) {
            if (! $request->is('api/*')) {
                return null;
            }

            return response()->json([
                'detail' => $e->getMessage() ?: 'Terjadi kesalahan',
            ], $e->getStatusCode());
        });
    })->create();
