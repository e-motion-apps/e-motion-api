<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\VoiDataImporter;

class VoiDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new VoiDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
