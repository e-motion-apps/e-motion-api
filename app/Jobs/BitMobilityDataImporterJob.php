<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BitMobilityDataImporter;

class BitMobilityDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new BitMobilityDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
