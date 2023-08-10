<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\DottDataImporter;

class DottDataImporterJob extends DataImporterJob
{
    public function handle(DottDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
