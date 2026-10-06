<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// 1. Import necessary Exception classes
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Your existing Spatie Permission Middleware aliases
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
        // ----------------------------------------------------------------
        // 1. ModelNotFoundException (404) - ID not found in DB
        // ----------------------------------------------------------------
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Resource not found.',
                    'detail' => 'The specific record ID you requested does not exist.'
                ], 404);
            }
        });

        // ----------------------------------------------------------------
        // 2. NotFoundHttpException (404) - Bad URL/Route
        // ----------------------------------------------------------------
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Endpoint not found.',
                    'detail' => 'The URL provided does not match any valid route.'
                ], 404);
            }
        });

        // ----------------------------------------------------------------
        // 3. ValidationException (422) - Form Input Errors
        // ----------------------------------------------------------------
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed.',
                    'errors' => $e->errors(),
                ], 422);
            }
            // Return null to let Laravel handle Web validation redirects automatically
            return null;
        });

        // ----------------------------------------------------------------
        // 4. AuthenticationException (401) - Not Logged In
        // ----------------------------------------------------------------
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthenticated.',
                    'detail' => 'Please log in or provide a valid API token.'
                ], 401);
            }
        });

        // ----------------------------------------------------------------
        // 5. AccessDeniedHttpException (403) - Permissions/Roles Failed
        // ----------------------------------------------------------------
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Forbidden.',
                    'detail' => 'You do not have the required role or permission.'
                ], 403);
            }
        });

        // ----------------------------------------------------------------
        // 6. ThrottleRequestsException (429) - Rate Limit Hit
        // ----------------------------------------------------------------
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Too Many Requests.',
                    'detail' => 'Please wait before trying again.'
                ], 429);
            }
        });

        // ----------------------------------------------------------------
        // 7. QueryException (500) - Database Crashes
        // ----------------------------------------------------------------
        $exceptions->render(function (QueryException $e, Request $request) {
            // Log the critical error so you can see it in storage/logs/laravel.log
            Log::critical('Database Error: ' . $e->getMessage());

            $message = 'Database error occurred.';
            $detail = app()->isProduction()
                ? 'Internal Server Error. Please contact support.'
                : $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $message,
                    'detail' => $detail
                ], 500);
            }
            
            // For Web: Render custom 500 page or fallback to redirect
            if (view()->exists('errors.500')) {
                return response()->view('errors.500', ['exception' => $e], 500);
            }
            return response('Internal Server Error', 500);
        });

    })->create();