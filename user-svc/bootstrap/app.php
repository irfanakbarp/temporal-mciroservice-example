<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            if (!($request->is('api/*') || $request->expectsJson())) {
                return null;
            }

            $statusCode = 500;
            $message = 'An error occurred';

            if ($e instanceof NotFoundHttpException) {
                $statusCode = 404;
                $message = 'Route not found';
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                $statusCode = 422;
                $message = 'Validation failed';
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'errors' => $e->errors()
                ], $statusCode);
            } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                $statusCode = 401;
                $message = 'Unauthenticated';
            } elseif ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                $statusCode = 403;
                $message = 'Unauthorized';
            } elseif ($e instanceof HttpExceptionInterface) {
                $statusCode = $e->getStatusCode();
                $message = $e->getMessage() ?: 'An error occurred';
            } elseif (config('app.debug')) {
                $message = $e->getMessage();
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], $statusCode);
        });
    })->create();
