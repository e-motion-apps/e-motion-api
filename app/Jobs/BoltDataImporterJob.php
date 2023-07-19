<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BoltDataImporter;

class BoltDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new BoltDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
