<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BaqmeDataImporter;

class BaqmeDataImporterJob extends DataImporterJob
{
    public function handle(BaqmeDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
