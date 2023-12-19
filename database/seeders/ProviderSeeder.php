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
use App\Importers\HopDataImporter;
use App\Importers\HoppDataImporter;
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
            ["name" => BeamDataImporter::getProviderName(), "color" => "#7347ff",   "url" => "https://ridebeam.com/", "android_url" => "https://play.google.com/store/apps/details?id=ride.beam", "ios_url" => "https://apps.apple.com/us/app/beam-ride-scooters/id1450999639"],
            ["name" => BerylDataImporter::getProviderName(), "color" => "#00e3c2", "url" => "https://beryl.cc/", "android_url" => "https://play.google.com/store/apps/details?id=cc.beryl.app", "ios_url" => "https://apps.apple.com/gb/app/beryl-bikes/id1450999639"],
            ["name" => BinBinDataImporter::getProviderName(), "color" => "#3dbcc8", "url" => "https://www.binbin.es/", "android_url" => "https://play.google.com/store/apps/details?id=com.binbin", "ios_url" => "https://apps.apple.com/es/app/binbin/id1450999639"],
            ["name" => BirdDataImporter::getProviderName(), "color" => "#26ccf0",   "url" => "https://www.bird.co/", "android_url" => "https://play.google.com/store/apps/details?id=co.bird.android", "ios_url" => "https://apps.apple.com/us/app/bird-enjoy-the-ride/id1260842311"],
            ["name" => BitMobilityDataImporter::getProviderName(), "color" => "#8da6e3", "url" => "https://www.bitmobility.com", "android_url" => "https://play.google.com/store/apps/details?id=com.bitmobility", "ios_url" => "https://apps.apple.com/us/app/bit-mobility/id1450999639"],
            ["name" => BoltDataImporter::getProviderName(), "color" => "#24ef0a0", "url" => "https://www.micromobility.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.micromobility.bolt", "ios_url" => "https://apps.apple.com/us/app/bolt-electric-scooters/id1450999639"],
            ["name" => DottDataImporter::getProviderName(), "color" => "#f5c604", "url" => "https://ridedott.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.dott", "ios_url" => "https://apps.apple.com/us/app/dott-ride-your-way/id1450999639"],
            ["name" => HopDataImporter::getProviderName(), "color" => "#ea1821", "url" => "https://www.hop.city/", "android_url" => "https://play.google.com/store/apps/details?id=city.hop", "ios_url" => "https://apps.apple.com/us/app/hop-city/id1450999639"],
            ["name" => HoppDataImporter::getProviderName(), "color" => "#1ce5be", "url" => "https://www.hoppscooters.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.hoppscooters", "ios_url" => "https://apps.apple.com/us/app/hopp-scooters/id1450999639"],
            ["name" => HulajDataImporter::getProviderName(), "color" => "#d6213f", "url" => "https://hulaj.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.hulaj", "ios_url" => "https://apps.apple.com/us/app/hulaj/id1450999639"],
            ["name" => LimeDataImporter::getProviderName(), "color" => "#00de00", "url" => "https://www.li.me/", "android_url" => "https://play.google.com/store/apps/details?id=com.limebike", "ios_url" => "https://apps.apple.com/us/app/lime-your-ride-anytime/id1450999639"],
            ["name" => LinkDataImporter::getProviderName(), "color" => "#def700", "url" => "https://link.city/", "android_url" => "https://play.google.com/store/apps/details?id=city.link", "ios_url" => "https://apps.apple.com/us/app/link-city/id1450999639"],
            ["name" => NeuronDataImporter::getProviderName(), "color" => "#445261", "url" => "https://www.neuron.sg/", "android_url" => "https://play.google.com/store/apps/details?id=sg.neuron", "ios_url" => "https://apps.apple.com/us/app/neuron-mobility/id1450999639"],
            ["name" => QuickDataImporter::getProviderName(), "color" => "#009ac7", "url" => "https://www.quick.co/", "android_url" => "https://play.google.com/store/apps/details?id=com.quick", "ios_url" => "https://apps.apple.com/us/app/quick-ride/id1450999639"],
            ["name" => SpinDataImporter::getProviderName(), "color" => "#ff5436", "url" => "https://www.spin.app/", "android_url" => "https://play.google.com/store/apps/details?id=app.spin", "ios_url" => "https://apps.apple.com/us/app/spin-scooters/id1450999639"],
            ["name" => SixtDataImporter::getProviderName(), "color" => "#f25a04", "url" => "https://www.sixt.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.sixt.reservation", "ios_url" => "https://apps.apple.com/us/app/sixt-rent-a-car/id1450999639"],
            ["name" => TierDataImporter::getProviderName(), "color" => "#0E1A50", "url" => "https://www.tier.app/", "android_url" => "https://play.google.com/store/apps/details?id=app.tier", "ios_url" => "https://apps.apple.com/us/app/tier/id1450999639"],
            ["name" => VoiDataImporter::getProviderName(), "color" => "#f46c63", "url" => "https://www.voiscooters.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.voiapp.voi", "ios_url" => "https://apps.apple.com/us/app/voi-scooters-get-magic-wheels/id1450999639"],
            ["name" => UrentDataImporter::getProviderName(), "color" => "#9400FF", "url" => "https://www.urent.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.urent", "ios_url" => "https://apps.apple.com/us/app/urent/id1450999639"],
            ["name" => ZwingsDataImporter::getProviderName(), "color" => "#abb8c3", "url" => "https://www.zwings.co.uk/", "android_url" => "https://play.google.com/store/apps/details?id=com.zwings", "ios_url" => "https://apps.apple.com/us/app/zwings/id1450999639"],
            ["name" => RydeDataImporter::getProviderName(), "color" => "#4dcb1f", "url" => "https://www.ryde.es/", "android_url" => "https://play.google.com/store/apps/details?id=com.ryde", "ios_url" => "https://apps.apple.com/us/app/ryde/id1450999639"],
            ["name" => VeoDataImporter::getProviderName(), "color" => "#000000", "url" => "https://www.veoride.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.veoride.rider", "ios_url" => "https://apps.apple.com/us/app/veoride/id1450999639"],
            ["name" => WindDataImporter::getProviderName(), "color" => "#fffa00", "url" => "https://www.wind.co/", "android_url" => "https://play.google.com/store/apps/details?id=co.wind", "ios_url" => "https://apps.apple.com/us/app/wind/id1450999639"],
            ["name" => WheeMoveDataImporter::getProviderName(), "color" => "#31682d", "url" => "https://www.wheemove.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.wheemove", "ios_url" => "https://apps.apple.com/us/app/wheemove/id1450999639"],
        ];

        foreach ($providers as $provider) {
            Provider::create([
                "name" => $provider["name"],
                "color" => $provider["color"],
                "url" => $provider["url"] ?? null,
                "android_url" => $provider["android_url"] ?? null,
                "ios_url" => $provider["ios_url"] ?? null,
            ]);
        }
    }
}
