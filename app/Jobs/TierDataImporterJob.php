<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\TierDataImporter;

class TierDataImporterJob extends DataImporterJob
{
    public function handle(TierDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
