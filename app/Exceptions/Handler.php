<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
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

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException) {


            // Separate error pages for Admin and Front
            if ($request->is('admin/*')) {
                return response()->view('errors.admin.404', [], 404);  // Admin 404 page
            } else {
                return response()->view('errors.front.404', [], 404);  // Front 404 page
            }
        }

        if ($e->getCode() === 500) {
            // Separate 500 pages
            if ($request->is('admin/*')) {
                return response()->view('errors.admin.500', [], 500);  // Admin 500 page
            } else {
                return response()->view('errors.front.500', [], 500);  // Front 500 page
            }
        }

        return parent::render($request, $e);
    }
}
