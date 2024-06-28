<?php

declare(strict_types=1);

return [
    "ssr" => [
        "enabled" => true,

        "url" => "http://127.0.0.1:13714",
    ],

    "testing" => [
        "ensure_pages_exist" => true,

        "page_paths" => [
            resource_path("js/Pages"),
        ],

        "page_extensions" => [
            "js",
            "jsx",
            "svelte",
            "ts",
            "tsx",
            "vue",
        ],
    ],
];
