<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\SixtDataImporter;

class SixtDataImporterJob extends DataImporterJob
{
    public function handle(SixtDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
