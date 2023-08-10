<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\NeuronDataImporter;

class NeuronDataImporterJob extends DataImporterJob
{
    public function handle(NeuronDataImporter $importer): void
    {
        $importer->setImportInfo($this->importInfoId)->extract()->transform();
    }
}
