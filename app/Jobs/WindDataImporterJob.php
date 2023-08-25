<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\WindDataImporter;

class WindDataImporterJob extends DataImporterJob
{
    public function handle(WindDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
