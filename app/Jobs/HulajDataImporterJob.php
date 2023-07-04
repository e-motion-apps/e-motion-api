<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\HulajDataImporter;

class HulajDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new HulajDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
