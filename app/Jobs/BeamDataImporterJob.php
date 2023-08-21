<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BeamDataImporter;

class BeamDataImporterJob extends DataImporterJob
{
    public function handle(BeamDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
