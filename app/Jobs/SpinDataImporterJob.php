<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\SpinDataImporter;

class SpinDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new SpinDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
