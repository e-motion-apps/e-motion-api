<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BerylDataImporter;

class BerylDataImporterJob extends DataImporterJob
{
    public function handle(BerylDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
