<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\HelbizDataImporter;

class HelbizDataImporterJob extends DataImporterJob
{
    public function handle(HelbizDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
