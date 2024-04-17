<?php

declare(strict_types=1);

namespace App\Console;

use App\Services\DataImporterService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function (): void {
            $service = new DataImporterService();
            $service->run("server");
        })->monthly();
        $schedule->command("sanctum:prune-expired --hours=24")->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . "/Commands");
    }
}
