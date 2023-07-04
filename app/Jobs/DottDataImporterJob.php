<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\DottDataImporter;

class DottDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new DottDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
