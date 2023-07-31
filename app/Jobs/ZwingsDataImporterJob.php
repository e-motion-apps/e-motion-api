<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\ZwingsDataImporter;

class ZwingsDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new ZwingsDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
