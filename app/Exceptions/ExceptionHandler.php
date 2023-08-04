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

    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);
        $statusCode = $response->status();

        $description = $this->getDescriptionByStatusCode($statusCode);

        return Inertia::render("Error", [
            "statusCode" => $statusCode,
            "description" => $description,
        ])
            ->toResponse($request)
            ->setStatusCode($statusCode);
    }

    protected function getDescriptionByStatusCode(int $statusCode): string
    {
        $descriptions = [
            Response::HTTP_METHOD_NOT_ALLOWED => "Sorry, the page you were looking for could not be found.",
            Response::HTTP_FORBIDDEN => "Sorry, the page you were looking for could not be found.",
            Response::HTTP_UNAUTHORIZED => "Sorry, the page you were looking for could not be found.",
            Response::HTTP_NOT_FOUND => "Sorry, the page you were looking for could not be found.",
            Response::HTTP_INTERNAL_SERVER_ERROR => "Oops! Something went wrong on our end. Please try again later.",
            Response::HTTP_SERVICE_UNAVAILABLE => "Oops! The service is currently unavailable. Please try again later.",
            Response::HTTP_TOO_MANY_REQUESTS => "Oops! Too many requests. Please try again later.",
            419 => "Your session has expired. Please refresh the page and try again.",
        ];

        return $descriptions[$statusCode] ?? "Oops. Something went wrong. Try again later.";
    }
}
