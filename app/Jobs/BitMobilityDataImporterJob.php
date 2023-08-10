<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BitMobilityDataImporter;

class BitMobilityDataImporterJob extends DataImporterJob
{
    public function handle(BitMobilityDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
