<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\FelyxDataImporter;

class FelyxDataImporterJob extends DataImporterJob
{
    public function handle(FelyxDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
