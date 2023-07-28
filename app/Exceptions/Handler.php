<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    public function render($request, Exception|\Throwable $e)
    {
        if ($e instanceof MethodNotAllowedHttpException) {
            return Inertia::render("Error", [
                "statusCode" => $e->getStatusCode(),
                "message" => "You don't have access to this page.",
            ]);
        }

        return parent::render($request, $e);
    }
}
