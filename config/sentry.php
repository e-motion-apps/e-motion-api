<?php

declare(strict_types=1);

return [
    "dsn" => env("SENTRY_LARAVEL_DSN", env("SENTRY_DSN")),

    "release" => env("SENTRY_RELEASE"),

    "environment" => env("SENTRY_ENVIRONMENT"),

    "sample_rate" => env("SENTRY_SAMPLE_RATE") === null ? 1.0 : (float)env("SENTRY_SAMPLE_RATE"),

    "traces_sample_rate" => env("SENTRY_TRACES_SAMPLE_RATE") === null ? null : (float)env("SENTRY_TRACES_SAMPLE_RATE"),

    "profiles_sample_rate" => env("SENTRY_PROFILES_SAMPLE_RATE") === null ? null : (float)env("SENTRY_PROFILES_SAMPLE_RATE"),

    "send_default_pii" => env("SENTRY_SEND_DEFAULT_PII", false),

    "breadcrumbs" => [
        "logs" => env("SENTRY_BREADCRUMBS_LOGS_ENABLED", true),

        "cache" => env("SENTRY_BREADCRUMBS_CACHE_ENABLED", true),

        "livewire" => env("SENTRY_BREADCRUMBS_LIVEWIRE_ENABLED", true),

        "sql_queries" => env("SENTRY_BREADCRUMBS_SQL_QUERIES_ENABLED", true),

        "sql_bindings" => env("SENTRY_BREADCRUMBS_SQL_BINDINGS_ENABLED", false),

        "queue_info" => env("SENTRY_BREADCRUMBS_QUEUE_INFO_ENABLED", true),

        "command_info" => env("SENTRY_BREADCRUMBS_COMMAND_JOBS_ENABLED", true),

        "http_client_requests" => env("SENTRY_BREADCRUMBS_HTTP_CLIENT_REQUESTS_ENABLED", true),
    ],

    "tracing" => [
        "queue_job_transactions" => env("SENTRY_TRACE_QUEUE_ENABLED", false),

        "queue_jobs" => env("SENTRY_TRACE_QUEUE_JOBS_ENABLED", true),

        "sql_queries" => env("SENTRY_TRACE_SQL_QUERIES_ENABLED", true),

        "sql_origin" => env("SENTRY_TRACE_SQL_ORIGIN_ENABLED", true),

        "views" => env("SENTRY_TRACE_VIEWS_ENABLED", true),

        "livewire" => env("SENTRY_TRACE_LIVEWIRE_ENABLED", true),

        "http_client_requests" => env("SENTRY_TRACE_HTTP_CLIENT_REQUESTS_ENABLED", true),

        "redis_commands" => env("SENTRY_TRACE_REDIS_COMMANDS", false),

        "redis_origin" => env("SENTRY_TRACE_REDIS_ORIGIN_ENABLED", true),

        "missing_routes" => env("SENTRY_TRACE_MISSING_ROUTES_ENABLED", false),

        "continue_after_response" => env("SENTRY_TRACE_CONTINUE_AFTER_RESPONSE", true),

        "default_integrations" => env("SENTRY_TRACE_DEFAULT_INTEGRATIONS_ENABLED", true),
    ],
];
