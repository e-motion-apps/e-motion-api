<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, Exception|\Throwable $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return back();
        }

        return parent::render($request, $e);
    }
}
