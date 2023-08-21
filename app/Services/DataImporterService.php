<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\BinBinDataImporterJob;
use App\Jobs\BerylDataImporterJob;
use App\Jobs\BirdDataImporterJob;
use App\Jobs\BitMobilityDataImporterJob;
use App\Jobs\BoltDataImporterJob;
use App\Jobs\DottDataImporterJob;
use App\Jobs\HulajDataImporterJob;
use App\Jobs\LimeDataImporterJob;
use App\Jobs\LinkDataImporterJob;
use App\Jobs\NeuronDataImporterJob;
use App\Jobs\QuickDataImporterJob;
use App\Jobs\RydeDataImporterJob;
use App\Jobs\SpinDataImporterJob;
use App\Jobs\TierDataImporterJob;
use App\Jobs\UrentDataImporterJob;
use App\Jobs\VoiDataImporterJob;
use App\Jobs\ZwingsDataImporterJob;
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
            new BinBinDataImporterJob($this->importInfoId),
            new BirdDataImporterJob($this->importInfoId),
            new BitMobilityDataImporterJob($this->importInfoId),
            new BoltDataImporterJob($this->importInfoId),
            new DottDataImporterJob($this->importInfoId),
            new HulajDataImporterJob($this->importInfoId),
            new LimeDataImporterJob($this->importInfoId),
            new LinkDataImporterJob($this->importInfoId),
            new NeuronDataImporterJob($this->importInfoId),
            new QuickDataImporterJob($this->importInfoId),
            new RydeDataImporterJob($this->importInfoId),
            new SpinDataImporterJob($this->importInfoId),
            new TierDataImporterJob($this->importInfoId),
            new UrentDataImporterJob($this->importInfoId),
            new VoiDataImporterJob($this->importInfoId),
            new ZwingsDataImporterJob($this->importInfoId),
            new BerylDataImporterJob($this->importInfoId),
        ])->finally(function (): void {
            ImportInfo::query()->where("id", $this->importInfoId)->update([
                "status" => "finished",
            ]);
        })->dispatch();
    }
}
