<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BirdDataImporter;

class BirdDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new BirdDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
