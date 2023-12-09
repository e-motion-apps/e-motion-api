<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\HoppDataImporter;

class HoppDataImporterJob extends DataImporterJob
{
    public function handle(HoppDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
