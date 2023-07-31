<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\ZwingsDataImporter;

class ZwingsDataImporterJob extends DataImporterJob
{
    public function handle(ZwingsDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
