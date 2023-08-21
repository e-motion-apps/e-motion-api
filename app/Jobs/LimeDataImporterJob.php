<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\LimeDataImporter;

class LimeDataImporterJob extends DataImporterJob
{
    public function handle(LimeDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
