<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\LinkDataImporter;

class LinkDataImporterJob extends DataImporterJob
{
    public function handle(LinkDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
