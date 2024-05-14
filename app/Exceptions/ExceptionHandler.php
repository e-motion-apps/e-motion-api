<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Sentry\Laravel\Integration;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ExceptionHandler extends Handler
{
    protected $dontFlash = [
        "current_password",
        "password",
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $exception): void {
            Integration::captureUnhandledException($exception);
        });
    }

    /**
     * @noinspection PhpParameterNameChangedDuringInheritanceInspection
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            if (Request::is("api/*")) {
                return response()->json([
                    "message" => $exception->getMessage(),
                    "errors" => $exception->errors(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            return back()->withErrors($exception->errors());
        }

        if ($exception instanceof OpenAiException) {
            return response()->json(["error" => $exception->getMessage()], $exception->getCode());
        }

        $response = parent::render($request, $exception);
        $statusCode = $response->status();

        app()->setLocale("en");

        $encryptedCookie = $request->cookie("locale");

        if ($encryptedCookie) {
            if (in_array($encryptedCookie, ["pl", "en"], true)) {
                app()->setLocale($encryptedCookie);
            } else {
                $decryptedCookie = Crypt::decryptString($encryptedCookie);

                $languageCode = substr($decryptedCookie, strpos($decryptedCookie, "|") + 1);

                if (in_array($languageCode, ["pl", "en"], true)) {
                    app()->setLocale($languageCode);
                }
            }
        }

        switch ($statusCode) {
            case Response::HTTP_INTERNAL_SERVER_ERROR:
            case Response::HTTP_SERVICE_UNAVAILABLE:
            case Response::HTTP_TOO_MANY_REQUESTS:
                $statusTitle = __($response->statusText());
                $statusDescription = $this->getDescriptionByStatusCode($statusCode);

                break;
            case 419:
                $statusTitle = __("Session Expired");
                $statusDescription = __("Your session has expired. Please log in and try again.");

                break;
            default:
                $statusTitle = __("Not Found");
                $statusDescription = __("Sorry, the page you were looking for could not be found.");
                $statusCode = Response::HTTP_NOT_FOUND;

                break;
        }

        if (Request::is("api/*")) {
            return response()->json([
                "statusTitle" => $statusTitle,
                "statusDescription" => $statusDescription,
            ], $statusCode);
        }

        return Inertia::render("Error", [
            "statusTitle" => $statusTitle,
            "statusDescription" => $statusDescription,
            "statusCode" => $statusCode,
        ])
            ->toResponse($request)
            ->setStatusCode($statusCode);
    }

    protected function getDescriptionByStatusCode(int $statusCode): string
    {
        $descriptions = [
            Response::HTTP_INTERNAL_SERVER_ERROR => __("Oops! Something went wrong on our end. Please try again later."),
            Response::HTTP_SERVICE_UNAVAILABLE => __("Oops! The service is currently unavailable. Please try again later."),
            Response::HTTP_TOO_MANY_REQUESTS => __("Oops! Too many requests. Please try again later."),
        ];

        return $descriptions[$statusCode] ?? __("Oops. Something went wrong. Try again later.");
    }
}
