<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;

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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (url_mobile()){
            if ($exception instanceof ModelNotFoundException) {
                return response()->view('themes.mobile.frontend.errors.404', [], 404);
            }
            return parent::render($request, $exception);
        }else{
            if ($exception instanceof ModelNotFoundException) {
                return response()->view('errors.404', [], 404);
            }
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => 'error',
                    'error' =>  $exception->getMessage(),
                    'message' => $exception->validator->errors()->all()[0]
                ], 200);
            }
            return parent::render($request, $exception);
        }
    }
}
