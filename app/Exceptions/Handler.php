<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function render($request, Throwable $e): \Illuminate\Http\Response|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|HttpResponse
    {
        return response()->json([
            'type' => 'error',
            'message' => $e->getMessage(),
        ], $this->getStatusCodeFromException($e));
    }

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    protected function getStatusCodeFromException(Throwable $e)
    {
        if ($e instanceof AccessDeniedHttpException || $e instanceof AuthorizationException) {
            return HttpResponse::HTTP_FORBIDDEN;
        }

        if ($e instanceof BadRequestHttpException) {
            return HttpResponse::HTTP_BAD_REQUEST;
        }

        if ($e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException) {
            return HttpResponse::HTTP_NOT_FOUND;
        }

        if ($e instanceof ValidationException) {
            return HttpResponse::HTTP_UNPROCESSABLE_ENTITY;
        }

        if ($e instanceof HttpException) {
            return $e->getStatusCode();
        }

        return HttpResponse::HTTP_INTERNAL_SERVER_ERROR;
    }
}
