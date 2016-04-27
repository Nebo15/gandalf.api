<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Nebo15\LumenApplicationable\Exceptions\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ValidationException::class,
        ModelNotFoundException::class,
        AuthorizationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, \Exception $e)
    {
        if (env('BUGSNAG_ENABLED')) {
            app('bugsnag')->notifyException($e, []);
        }

        $http_code = 500;
        $error_code = 'internal_server_error';
        $error_message = null;

        $meta = [];

        if ($e instanceof ValidationException) {
            return response()->json([
                'meta' => [
                    'code' => 422,
                    'error' => 'validation',
                ],
                'data' => $e->errors(),
            ], 422, ['Content-Type' => 'application/json']);
        } elseif ($e instanceof AuthorizationException) {
            $http_code = 401;
            $error_code = 'unauthorized';
            $meta['error_message'] = $e->getMessage();
        } elseif ($e instanceof ModelNotFoundException) {
            $http_code = 404;
            $error_code = $this->formatModelName($e->getModel()) . '_not_found';
        } elseif ($e instanceof HttpException) {
            $http_code = $e->getStatusCode();
            $error_code = $e->getMessage() ?: 'http';
        } elseif ($e instanceof AccessDeniedException) {
            $http_code = 403;
            $error_code = 'access_denied';
            $meta['error_message'] = $e->getMessage();

        }

        if ($http_code === 500 and env('APP_DEBUG') === true) {
            return $e->__toString();
        }

        $meta['code'] = $http_code;
        $meta['error'] = $error_code;

        if (empty($meta['error_message']) and $error_msg = config("errors.$error_code")) {
            $meta['error_message'] = $error_msg;
        }

        return response()->json(['meta' => $meta], $http_code, ['Content-Type' => 'application/json']);
    }

    private function formatModelName($model)
    {
        $name = preg_replace('/\B([A-Z])/', '_$1', explode('\\', $model));

        return strtolower(end($name));
    }
}
