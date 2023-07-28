<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class MapboxGeocodingServiceException extends Exception
{
    protected $message = "Invalid data retrieved from Mapbox API.";
}
