<?php

declare(strict_types=1);
use Blumilk\BLT\Helpers\ArrayHelper;
use Blumilk\BLT\Helpers\BooleanHelper;
use Blumilk\BLT\Helpers\ClassHelper;
use Blumilk\BLT\Helpers\ContextHelper;
use Blumilk\BLT\Helpers\LaravelRelations;
use Blumilk\BLT\Helpers\NullableHelper;

return [
    "namespaces" => [
        "default" => "App\\",
        "types" => [
            "Role" => "Spatie\Permission\Models\\",
            "Provider" => "App\Models\\",
        ],
    ],
    "helpers" => [
        "array" => ArrayHelper::class,
        "boolean" => BooleanHelper::class,
        "class" => ClassHelper::class,
        "context" => ContextHelper::class,
        "nullable" => NullableHelper::class,
        "laravelRelations" => LaravelRelations::class,
    ],
];
