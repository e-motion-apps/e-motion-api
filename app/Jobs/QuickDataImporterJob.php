<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\QuickDataImporter;

class QuickDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new QuickDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
