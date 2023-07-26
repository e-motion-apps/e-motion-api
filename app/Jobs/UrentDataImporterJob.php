<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\UrentDataImporter;

class UrentDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new UrentDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
