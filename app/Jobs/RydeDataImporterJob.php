<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\RydeDataImporter;

class RydeDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new RydeDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
