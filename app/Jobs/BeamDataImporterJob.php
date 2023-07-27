<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BeamDataImporter;

class BeamDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new BeamDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
