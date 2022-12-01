<?php

namespace App\Exceptions;

use App\Notifications\ExceptionCought;
use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Classes\Slack;
use App\Http\Requests\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }

//        Log::info($exception->getMessage(), [
//            'url'   => Request::url(),
//            'input' => Request::all()
//        ]);

//        if ($exception instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
//            return response([
//                'message' => 'Method Not Allowed.',
//                'type'    => 'error',
//            ], 404);
//        }

//        if (extension_loaded('newrelic')) {
//            newrelic_notice_error($exception);
//        }

//        $requestUrl = Request::url();
        Slack::send(new ExceptionCought($exception));


        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        return parent::render($request, $e);
    }
}
