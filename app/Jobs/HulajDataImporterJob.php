<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\HulajDataImporter;

class HulajDataImporterJob extends DataImporterJob
{
    public function handle(HulajDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
