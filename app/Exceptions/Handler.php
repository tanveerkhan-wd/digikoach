<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Response;
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
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        //dd($exception);
        if ($request->expectsJson()) {
            //echo $exception->getMessage();exit;
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($exception instanceof AuthenticationException) {
                $status = Response::HTTP_UNAUTHORIZED;
            } elseif ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                $status = Response::HTTP_FORBIDDEN;
                //return response()->json(['token_expired'], $exception->getStatusCode());
            } elseif ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                $status = Response::HTTP_FORBIDDEN;
                //return response()->json(['token_invalid'], $exception->getStatusCode());
            } elseif ($exception instanceof \Tymon\JWTAuth\Exceptions\TokenBlacklistedException) {
                $status = Response::HTTP_FORBIDDEN;
                //return response()->json(['token_invalid'], $exception->getStatusCode());
            } elseif ($exception instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                $status = Response::HTTP_UNAUTHORIZED;
            } elseif ($exception instanceof HttpResponseException) {
                $status = Response::HTTP_INTERNAL_SERVER_ERROR;
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                $status = Response::HTTP_METHOD_NOT_ALLOWED;
                $exception = new MethodNotAllowedHttpException([], 'HTTP_METHOD_NOT_ALLOWED', $exception);
            } elseif ($exception instanceof NotFoundHttpException) {
                $status = Response::HTTP_NOT_FOUND;
                $exception = new NotFoundHttpException('HTTP_NOT_FOUND', $exception);
            } elseif ($exception instanceof AuthorizationException) {
                $status = Response::HTTP_FORBIDDEN;
                $exception = new AuthorizationException('HTTP_FORBIDDEN', $status);
            } elseif ($exception instanceof \Dotenv\Exception\ValidationException && $exception->getResponse()) {
                $status = Response::HTTP_BAD_REQUEST;
                $exception = new \Dotenv\Exception\ValidationException('HTTP_BAD_REQUEST', $status, $exception);
            } elseif ($exception instanceof ModelNotFoundException) {
                $status = Response::HTTP_NOT_FOUND;
                $exception = new NotFoundHttpException('HTTP_NOT_FOUND', $exception);
            } elseif ($exception) {
                $exception = new HttpException($status, 'HTTP_INTERNAL_SERVER_ERROR');
            }

            return response()->json([
                'message' => $exception->getMessage()
            ], $status);
        }

        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            //dd($exception);
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        /* $guard = array_get($exception->guards(), 0);
        switch ($guard) {
            case 'admin':
                $login = 'backend.login';
                break;
            case 'api':
                $login = 'v1.auth.login';
                break;
            default:
                $login = 'home';
                break;
        }

        return redirect()->guest(route($login)); */
    }
}
