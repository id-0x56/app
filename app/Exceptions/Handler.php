<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var string[]
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var string[]
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void|JsonResponse
     */
    public function register()
    {
        $this->renderable(function (Throwable $e) {
            return response()
                ->json([
                    'error' => $e->getMessage()
                ], $e->getCode() ?: JsonResponse::HTTP_BAD_REQUEST);
        });
    }
}
