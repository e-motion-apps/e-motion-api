<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BirdDataImporter;

class BirdDataImporterJob extends DataImporterJob
{
    public function handle(BirdDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
