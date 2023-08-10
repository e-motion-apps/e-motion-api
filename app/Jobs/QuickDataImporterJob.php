<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\QuickDataImporter;

class QuickDataImporterJob extends DataImporterJob
{
    public function handle(QuickDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
