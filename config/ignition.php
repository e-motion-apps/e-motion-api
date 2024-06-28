<?php

declare(strict_types=1);

use Spatie\Backtrace\Arguments\Reducers\ArrayArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\BaseTypeArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\ClosureArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\DateTimeArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\DateTimeZoneArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\EnumArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\StdClassArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\StringableArgumentReducer;
use Spatie\Backtrace\Arguments\Reducers\SymphonyRequestArgumentReducer;
use Spatie\Ignition\Solutions\SolutionProviders\BadMethodCallSolutionProvider;
use Spatie\Ignition\Solutions\SolutionProviders\MergeConflictSolutionProvider;
use Spatie\Ignition\Solutions\SolutionProviders\UndefinedPropertySolutionProvider;
use Spatie\LaravelIgnition\ArgumentReducers\CollectionArgumentReducer;
use Spatie\LaravelIgnition\ArgumentReducers\ModelArgumentReducer;
use Spatie\LaravelIgnition\Recorders\DumpRecorder\DumpRecorder;
use Spatie\LaravelIgnition\Recorders\JobRecorder\JobRecorder;
use Spatie\LaravelIgnition\Recorders\LogRecorder\LogRecorder;
use Spatie\LaravelIgnition\Recorders\QueryRecorder\QueryRecorder;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\DefaultDbNameSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\GenericLaravelExceptionSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\IncorrectValetDbCredentialsSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\InvalidRouteActionSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\MissingAppKeySolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\MissingColumnSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\MissingImportSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\MissingLivewireComponentSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\MissingMixManifestSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\MissingViteManifestSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\OpenAiSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\RunningLaravelDuskInProductionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\SailNetworkSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\TableNotFoundSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\UndefinedViewVariableSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\UnknownMariadbCollationSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\UnknownMysql8CollationSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\UnknownValidationSolutionProvider;
use Spatie\LaravelIgnition\Solutions\SolutionProviders\ViewNotFoundSolutionProvider;

return [
    "editor" => env("IGNITION_EDITOR", "phpstorm"),

    "theme" => env("IGNITION_THEME", "auto"),

    "enable_share_button" => env("IGNITION_SHARING_ENABLED", true),

    "register_commands" => env("REGISTER_IGNITION_COMMANDS", false),

    "solution_providers" => [
        BadMethodCallSolutionProvider::class,
        MergeConflictSolutionProvider::class,
        UndefinedPropertySolutionProvider::class,

        IncorrectValetDbCredentialsSolutionProvider::class,
        MissingAppKeySolutionProvider::class,
        DefaultDbNameSolutionProvider::class,
        TableNotFoundSolutionProvider::class,
        MissingImportSolutionProvider::class,
        InvalidRouteActionSolutionProvider::class,
        ViewNotFoundSolutionProvider::class,
        RunningLaravelDuskInProductionProvider::class,
        MissingColumnSolutionProvider::class,
        UnknownValidationSolutionProvider::class,
        MissingMixManifestSolutionProvider::class,
        MissingViteManifestSolutionProvider::class,
        MissingLivewireComponentSolutionProvider::class,
        UndefinedViewVariableSolutionProvider::class,
        GenericLaravelExceptionSolutionProvider::class,
        OpenAiSolutionProvider::class,
        SailNetworkSolutionProvider::class,
        UnknownMysql8CollationSolutionProvider::class,
        UnknownMariadbCollationSolutionProvider::class,
    ],

    "ignored_solution_providers" => [],

    "enable_runnable_solutions" => env("IGNITION_ENABLE_RUNNABLE_SOLUTIONS"),

    "remote_sites_path" => env("IGNITION_REMOTE_SITES_PATH", base_path()),
    "local_sites_path" => env("IGNITION_LOCAL_SITES_PATH", ""),

    "housekeeping_endpoint_prefix" => "_ignition",

    "settings_file_path" => "",

    "recorders" => [
        DumpRecorder::class,
        JobRecorder::class,
        LogRecorder::class,
        QueryRecorder::class,
    ],

    "open_ai_key" => env("IGNITION_OPEN_AI_KEY"),

    "with_stack_frame_arguments" => true,

    "argument_reducers" => [
        BaseTypeArgumentReducer::class,
        ArrayArgumentReducer::class,
        StdClassArgumentReducer::class,
        EnumArgumentReducer::class,
        ClosureArgumentReducer::class,
        DateTimeArgumentReducer::class,
        DateTimeZoneArgumentReducer::class,
        SymphonyRequestArgumentReducer::class,
        ModelArgumentReducer::class,
        CollectionArgumentReducer::class,
        StringableArgumentReducer::class,
    ],
];
