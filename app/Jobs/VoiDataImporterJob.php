<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\VoiDataImporter;

class VoiDataImporterJob extends DataImporterJob
{
    public function handle(VoiDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
