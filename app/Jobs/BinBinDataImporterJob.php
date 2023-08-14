<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\BinBinDataImporter;

class BinBinDataImporterJob extends DataImporterJob
{
    public function handle(BinBinDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
