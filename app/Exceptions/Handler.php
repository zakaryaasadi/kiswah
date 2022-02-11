<?php

namespace App\Exceptions;


use Exception;
use Laravel\Passport\Exceptions\MissingScopeException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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

//
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException && $request->is('api/*')) {
            return response()->json(['message' => 'Data not found !!', 'status' => 'error', 'status_code' => 404], 404);
        }

        if ($exception instanceof MissingScopeException && $request->is('api/*')) {
            return response()->json(['message' => 'You cannot access this route', 'status' => 'error', 'status_code' => 403], 403);
        }
        return parent::render($request, $exception);
    }
}
