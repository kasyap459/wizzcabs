<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        if ( in_array('admin', $exception->guards()) ) {
            return redirect('admin/login');
        }

        if ( in_array('provider', $exception->guards()) ) {
            return redirect('provider/login');
        }

        if ( in_array('dispatcher', $exception->guards()) ) {
            return redirect('dispatcher/login');
        }

        if ( in_array('partner', $exception->guards()) ) {
            return redirect('partner/login');
        }

        if ( in_array('corporate', $exception->guards()) ) {
            return redirect('corporate/login');
        }

        if ( in_array('hotel', $exception->guards()) ) {
            return redirect('hotel/login');
        }

        return redirect()->guest('login');
    }}
