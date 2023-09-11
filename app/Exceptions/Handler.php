<?php

namespace App\Exceptions;

use http\Env\Request;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use App\Http\traits\responseTrait;
class Handler extends ExceptionHandler
{
    use responseTrait;
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
        $this->reportable(function (\Exception $e,Request $request) {
            if ($request->is('api/*')) {
                return $this->errorResponse(statusCode: 500, message: 'an error accorded');
            }
        });
    }
}
