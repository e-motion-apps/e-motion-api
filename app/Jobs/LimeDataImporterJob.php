<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\LimeDataImporter;

class LimeDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new LimeDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
