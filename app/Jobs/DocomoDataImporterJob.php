<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\DocomoDataImporter;

class DocomoDataImporterJob extends DataImporterJob
{
    public function handle(DocomoDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
