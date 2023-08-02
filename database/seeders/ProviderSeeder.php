<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Importers\BirdDataImporter;
use App\Importers\BitMobilityDataImporter;
use App\Importers\BoltDataImporter;
use App\Importers\DottDataImporter;
use App\Importers\HulajDataImporter;
use App\Importers\LimeDataImporter;
use App\Importers\NeuronDataImporter;
use App\Importers\QuickDataImporter;
use App\Importers\RydeDataImporter;
use App\Importers\SpinDataImporter;
use App\Importers\UrentDataImporter;
use App\Importers\VoiDataImporter;
use App\Importers\ZwingsDataImporter;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ["name" => BirdDataImporter::getProviderName(), "color" => "#26ccf0"],
            ["name" => BitMobilityDataImporter::getProviderName(), "color" => "#8da6e3"],
            ["name" => BoltDataImporter::getProviderName(), "color" => "#24f0a0"],
            ["name" => DottDataImporter::getProviderName(), "color" => "#f5c604"],
            ["name" => HulajDataImporter::getProviderName(), "color" => "#d6213f"],
            ["name" => LimeDataImporter::getProviderName(), "color" => "#00de00"],
            ["name" => NeuronDataImporter::getProviderName(), "color" => "#445261"],
            ["name" => QuickDataImporter::getProviderName(), "color" => "#009ac7"],
            ["name" => SpinDataImporter::getProviderName(), "color" => "#ff5436"],
            ["name" => VoiDataImporter::getProviderName(), "color" => "#f46c63"],
            ["name" => UrentDataImporter::getProviderName(), "color" => "#9400FF"],
            ["name" => ZwingsDataImporter::getProviderName(), "color" => "#abb8c3"],
            ["name" => RydeDataImporter::getProviderName(), "color" => "#4dcb1f"],
        ];

        foreach ($providers as $provider) {
            Provider::create([
                "name" => $provider["name"],
                "color" => $provider["color"],
            ]);
        }
    }
}
