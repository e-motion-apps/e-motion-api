<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BoltDataImporter;

class BoltDataImporterJob extends DataImporterJob
{
    public function handle(BoltDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
