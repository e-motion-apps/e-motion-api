<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\GoSharingDataImporter;

class GoSharingDataImporterJob extends DataImporterJob
{
    public function handle(GoSharingDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
