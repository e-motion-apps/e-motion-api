<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Importers\BeamDataImporter;
use App\Importers\BerylDataImporter;
use App\Importers\BinBinDataImporter;
use App\Importers\BirdDataImporter;
use App\Importers\BitMobilityDataImporter;
use App\Importers\BoltDataImporter;
use App\Importers\DottDataImporter;
use App\Importers\HulajDataImporter;
use App\Importers\LimeDataImporter;
use App\Importers\LinkDataImporter;
use App\Importers\NeuronDataImporter;
use App\Importers\QuickDataImporter;
use App\Importers\RydeDataImporter;
use App\Importers\SixtDataImporter;
use App\Importers\SpinDataImporter;
use App\Importers\TierDataImporter;
use App\Importers\UrentDataImporter;
use App\Importers\VeoDataImporter;
use App\Importers\VoiDataImporter;
use App\Importers\WheeMoveDataImporter;
use App\Importers\WindDataImporter;
use App\Importers\ZwingsDataImporter;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ["name" => BeamDataImporter::getProviderName(), "color" => "#7347ff"],
            ["name" => BerylDataImporter::getProviderName(), "color" => "#00e3c2"],
            ["name" => BinBinDataImporter::getProviderName(), "color" => "#3dbcc8"],
            ["name" => BirdDataImporter::getProviderName(), "color" => "#26ccf0"],
            ["name" => BitMobilityDataImporter::getProviderName(), "color" => "#8da6e3"],
            ["name" => BoltDataImporter::getProviderName(), "color" => "#24f0a0"],
            ["name" => DottDataImporter::getProviderName(), "color" => "#f5c604"],
            ["name" => HulajDataImporter::getProviderName(), "color" => "#d6213f"],
            ["name" => LimeDataImporter::getProviderName(), "color" => "#00de00"],
            ["name" => LinkDataImporter::getProviderName(), "color" => "#def700"],
            ["name" => NeuronDataImporter::getProviderName(), "color" => "#445261"],
            ["name" => QuickDataImporter::getProviderName(), "color" => "#009ac7"],
            ["name" => SpinDataImporter::getProviderName(), "color" => "#ff5436"],
            ["name" => SixtDataImporter::getProviderName(), "color" => "#f25a04"],
            ["name" => TierDataImporter::getProviderName(), "color" => "#0E1A50"],
            ["name" => VoiDataImporter::getProviderName(), "color" => "#f46c63"],
            ["name" => UrentDataImporter::getProviderName(), "color" => "#9400FF"],
            ["name" => ZwingsDataImporter::getProviderName(), "color" => "#abb8c3"],
            ["name" => RydeDataImporter::getProviderName(), "color" => "#4dcb1f"],
            ["name" => VeoDataImporter::getProviderName(), "color" => "#000000"],
            ["name" => WindDataImporter::getProviderName(), "color" => "#fffa00"],
            ["name" => WheeMoveDataImporter::getProviderName(), "color" => "#31682d"],
        ];

        foreach ($providers as $provider) {
            Provider::create([
                "name" => $provider["name"],
                "color" => $provider["color"],
            ]);
        }
    }
}
