<?php

declare(strict_types=1);
use App\Models\Provider;
use Blumilk\BLT\Helpers\ArrayHelper;
use Blumilk\BLT\Helpers\BooleanHelper;
use Blumilk\BLT\Helpers\ClassHelper;
use Blumilk\BLT\Helpers\ContextHelper;
use Blumilk\BLT\Helpers\LaravelRelations;
use Blumilk\BLT\Helpers\NullableHelper;
use Spatie\Permission\Models\Role;

return [
    "namespaces" => [
        "default" => "App\\",
        "types" => [
            "role" => Role::class,
            "provider" => Provider::class,
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
