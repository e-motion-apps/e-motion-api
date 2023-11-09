<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\WheeMoveDataImporter;

class WheeMoveDataImporterJob extends DataImporterJob
{
    public function handle(WheeMoveDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
