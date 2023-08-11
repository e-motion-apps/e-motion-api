<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\UrentDataImporter;

class UrentDataImporterJob extends DataImporterJob
{
    public function handle(UrentDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
