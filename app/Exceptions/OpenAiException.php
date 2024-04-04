<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class OpenAiException extends Exception
{
    public function __construct($message = "OpenAI API connection error", $code = 500)
    {
        parent::__construct($message, $code);
    }
}
