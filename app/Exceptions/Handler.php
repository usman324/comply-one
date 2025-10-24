<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
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

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $this->reportable(function (Throwable $e) {
            if (config('logging.channels.slack_dev_exceptions_logs.url')) {
                Log::channel('slack_dev_exceptions_logs')->error($e->getMessage(), [
                    [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'code' => $e->getCode(),
                        'line' => $e->getLine(),
                    ],
                    ...(collect($e->getTrace())->take(5)->toArray()),
                ]);
            }
        });

        // Override the NotFoundHttpException exception.
        $this->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json(['status' => 'failed', 'message' => 'server.exceptions_data_not_found'], 404);
        });
    }
}
