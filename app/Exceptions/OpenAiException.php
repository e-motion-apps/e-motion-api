<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class OpenAiException extends Exception
{
    protected $message = "OpenAI API connection error";
    protected $code = Response::HTTP_INTERNAL_SERVER_ERROR;
}
