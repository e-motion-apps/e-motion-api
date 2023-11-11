<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\HopDataImporter;

class HopDataImporterJob extends DataImporterJob
{
    public function handle(HopDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
