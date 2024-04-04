<?php

declare(strict_types=1);

use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;

return [
    "api_path" => "api",
    "api_domain" => null,
    "export_path" => "api.json",
    "theme" => "light",
    "info" => [
        "version" => env("API_VERSION", "0.0.1"),
        "description" => "",
    ],
    "ui" => [
        "hide_try_it" => false,
        "logo" => "",
        "try_it_credentials_policy" => "include",
    ],
    "servers" => null,
    "middleware" => [
        "web",
        RestrictedDocsAccess::class,
    ],
    "extensions" => [],
];
