<?php

declare(strict_types=1);

namespace App\Services;

use App\Jobs\BeamDataImporterJob;
use App\Jobs\BerylDataImporterJob;
use App\Jobs\BinBinDataImporterJob;
use App\Jobs\BirdDataImporterJob;
use App\Jobs\BitMobilityDataImporterJob;
use App\Jobs\BoltDataImporterJob;
use App\Jobs\DocomoDataImporterJob;
use App\Jobs\DottDataImporterJob;
use App\Jobs\HopDataImporterJob;
use App\Jobs\HoppDataImporterJob;
use App\Jobs\HulajDataImporterJob;
use App\Jobs\LimeDataImporterJob;
use App\Jobs\LinkDataImporterJob;
use App\Jobs\NeuronDataImporterJob;
use App\Jobs\QuickDataImporterJob;
use App\Jobs\RydeDataImporterJob;
use App\Jobs\SixtDataImporterJob;
use App\Jobs\SpinDataImporterJob;
use App\Jobs\TierDataImporterJob;
use App\Jobs\UrentDataImporterJob;
use App\Jobs\VeoDataImporterJob;
use App\Jobs\VoiDataImporterJob;
use App\Jobs\WheeMoveDataImporterJob;
use App\Jobs\WindDataImporterJob;
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
            new BeamDataImporterJob($this->importInfoId),
            new BerylDataImporterJob($this->importInfoId),
            new BinBinDataImporterJob($this->importInfoId),
            new BirdDataImporterJob($this->importInfoId),
            new BitMobilityDataImporterJob($this->importInfoId),
            new BoltDataImporterJob($this->importInfoId),
            new DottDataImporterJob($this->importInfoId),
            new HulajDataImporterJob($this->importInfoId),
            new LimeDataImporterJob($this->importInfoId),
            new HoppDataImporterJob($this->importInfoId),
            new LinkDataImporterJob($this->importInfoId),
            new NeuronDataImporterJob($this->importInfoId),
            new DocomoDataImporterJob($this->importInfoId),
            new QuickDataImporterJob($this->importInfoId),
            new RydeDataImporterJob($this->importInfoId),
            new SpinDataImporterJob($this->importInfoId),
            new TierDataImporterJob($this->importInfoId),
            new UrentDataImporterJob($this->importInfoId),
            new VoiDataImporterJob($this->importInfoId),
            new VeoDataImporterJob($this->importInfoId),
            new WindDataImporterJob($this->importInfoId),
            new WheeMoveDataImporterJob($this->importInfoId),
            new HopDataImporterJob($this->importInfoId),
            new ZwingsDataImporterJob($this->importInfoId),
            new SixtDataImporterJob($this->importInfoId),
        ])->finally(function (): void {
            ImportInfo::query()->where("id", $this->importInfoId)->update([
                "status" => "finished",
            ]);
        })->dispatch();
    }
}
