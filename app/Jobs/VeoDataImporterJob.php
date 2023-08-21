<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\VeoDataImporter;

class VeoDataImporterJob extends DataImporterJob
{
    public function handle(VeoDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
