<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\RydeDataImporter;

class RydeDataImporterJob extends DataImporterJob
{
    public function handle(RydeDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
