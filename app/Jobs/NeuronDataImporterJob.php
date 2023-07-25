<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Importers\NeuronDataImporter;

class NeuronDataImporterJob extends DataImporterJob
{
    public function handle(): void
    {
        $importer = new NeuronDataImporter($this->importInfoId);
        $importer->extract()->transform();
    }
}
