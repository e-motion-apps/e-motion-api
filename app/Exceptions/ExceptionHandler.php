<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionHandler extends Handler
{
    protected $dontFlash = [
        "current_password",
        "password",
        "password_confirmation",
    ];
    protected array $handleByInertia = [
        Response::HTTP_INTERNAL_SERVER_ERROR,
        Response::HTTP_SERVICE_UNAVAILABLE,
        Response::HTTP_TOO_MANY_REQUESTS,
        419,
        Response::HTTP_NOT_FOUND,
        Response::HTTP_FORBIDDEN,
        Response::HTTP_UNAUTHORIZED,
    ];

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        $statusCode = $response->status();

        switch ($statusCode) {
            case Response::HTTP_METHOD_NOT_ALLOWED:
            case Response::HTTP_FORBIDDEN:
            case Response::HTTP_UNAUTHORIZED:
                $statusCode = Response::HTTP_NOT_FOUND;

                break;
            default:
                $statusCode = Response::HTTP_NOT_FOUND;

                break;
        }

        if (in_array($statusCode, $this->handleByInertia, strict: true)) {
            return Inertia::render("Error", [
                "statusCode" => $statusCode,
                "description" => "Sorry, the page you were looking for could not be found.",
            ])
                ->toResponse($request)
                ->setStatusCode($statusCode);
        }

        return $response->setStatusCode($statusCode);
    }
}
