<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\BitMobilityDataImporterJob;
use App\Jobs\BoltDataImporterJob;
use App\Jobs\DottDataImporterJob;
use App\Jobs\HulajDataImporterJob;
use App\Jobs\LimeDataImporterJob;
use App\Jobs\QuickDataImporterJob;
use App\Jobs\SpinDataImporterJob;
use App\Models\ImportInfo;
use Illuminate\Support\Facades\Bus;

class DataImporterService
{
    private int $importInfoId;

    public function run(string $whoRunsIt = "admin"): void
    {
        $importInfo = ImportInfo::query()->create([
            "who_runs_it" => $whoRunsIt,
            "status" => "running",
        ]);

        $this->importInfoId = $importInfo->id;

        Bus::batch([
            new BitMobilityDataImporterJob($this->importInfoId),
            new BoltDataImporterJob($this->importInfoId),
            new DottDataImporterJob($this->importInfoId),
            new HulajDataImporterJob($this->importInfoId),
            new LimeDataImporterJob($this->importInfoId),
            new QuickDataImporterJob($this->importInfoId),
            new SpinDataImporterJob($this->importInfoId),
        ])->finally(function (): void {
            ImportInfo::query()->where("id", $this->importInfoId)->update([
                "status" => "finished",
            ]);
        })->onQueue("importers")->dispatch();
    }
}
