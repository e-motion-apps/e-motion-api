<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Importers\BaqmeDataImporter;
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
use Illuminate\Support\Facades\Storage;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $providers = [
            ["name" => BaqmeDataImporter::getProviderName(), "color" => "#50E3C2", "url" => "https://baqme.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.baqme.android", "ios_url" => "https://apps.apple.com/us/app/baqme/id1538722828"],
            ["name" => BeamDataImporter::getProviderName(), "color" => "#7347ff",   "url" => "https://www.ridebeam.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.escooterapp&pli=1", "ios_url" => "https://apps.apple.com/app/id1427114484"],
            ["name" => BerylDataImporter::getProviderName(), "color" => "#00e3c2", "url" => "https://beryl.cc/", "android_url" => "https://play.google.com/store/apps/details?id=cc.beryl.basis", "ios_url" => "https://apps.apple.com/app/id1386768364"],
            ["name" => BinBinDataImporter::getProviderName(), "color" => "#3dbcc8", "url" => "https://www.binbin.tech/", "android_url" => "https://play.google.com/store/apps/details?id=com.BINBIN&hl=en_US", "ios_url" => "https://apps.apple.com/us/app/binbin-scooters/id1483635924"],
            ["name" => BirdDataImporter::getProviderName(), "color" => "#26ccf0",   "url" => "https://www.bird.co/", "android_url" => "https://play.google.com/store/apps/details?id=co.bird.android", "ios_url" => "https://apps.apple.com/us/app/bird-be-free-enjoy-the-ride/id1260842311"],
            ["name" => BitMobilityDataImporter::getProviderName(), "color" => "#8da6e3", "url" => "https://bitmobility.it/en/", "android_url" => "https://play.google.com/store/apps/details?id=it.bitmobility.bit", "ios_url" => "https://apps.apple.com/it/app/bit/id1464155063"],
            ["name" => BoltDataImporter::getProviderName(), "color" => "#24f0a0", "url" => "https://bolt.eu/pl-pl/scooters/", "android_url" => "https://play.google.com/store/apps/details?id=ee.mtakso.client&hl=pl&gl=US", "ios_url" => "https://apps.apple.com/pl/app/bolt-przejazdy-hulajnogi/id675033630?l=pl"],
            ["name" => DottDataImporter::getProviderName(), "color" => "#f5c604", "url" => "https://ridedott.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.ridedott.rider&pli=1", "ios_url" => "https://apps.apple.com/us/app/dott-unlock-your-city/id1440301673"],
            ["name" => HopDataImporter::getProviderName(), "color" => "#ea1821", "url" => "https://hop.bike/en/", "android_url" => "https://play.google.com/store/apps/details?id=com.hoplagit.rider&hl=pl&gl=US", "ios_url" => "https://apps.apple.com/pl/app/hop-enjoy-the-city/id1487640704?l=pl"],
            ["name" => HoppDataImporter::getProviderName(), "color" => "#1ce5be", "url" => "https://hopp.bike/", "android_url" => "https://play.google.com/store/apps/details?id=bike.hopp", "ios_url" => "https://apps.apple.com/us/app/hopp-scooters/id1471324642?ls=1"],
            ["name" => HulajDataImporter::getProviderName(), "color" => "#d6213f", "url" => "https://hulaj.eu/", "android_url" => "https://play.google.com/store/apps/details?id=eu.hulaj&hl=pl&gl=US"],
            ["name" => LimeDataImporter::getProviderName(), "color" => "#00de00", "url" => "https://www.li.me/", "android_url" => "https://play.google.com/store/apps/details?id=com.limebike", "ios_url" => "https://apps.apple.com/app/id1199780189"],
            ["name" => LinkDataImporter::getProviderName(), "color" => "#def700", "url" => "https://link.city/", "android_url" => "https://play.google.com/store/apps/details?id=com.superpedestrian.link", "ios_url" => "https://apps.apple.com/US/app/id1487864428?mt=8"],
            ["name" => NeuronDataImporter::getProviderName(), "color" => "#445261", "url" => "https://www.rideneuron.com/", "android_url" => "https://go.onelink.me/app/Neuron", "ios_url" => "https://go.onelink.me/app/NeuroniOS"],
            ["name" => QuickDataImporter::getProviderName(), "color" => "#009ac7", "url" => "https://quick-app.eu/en/", "android_url" => "https://play.google.com/store/apps/details?id=eu.quick_app.eu", "ios_url" => "https://apps.apple.com/us/app/quickapp/id1461661144"],
            ["name" => SpinDataImporter::getProviderName(), "color" => "#ff5436", "url" => "https://www.spin.app/", "android_url" => "https://play.google.com/store/apps/details?hl=en_US&id=pm.spin", "ios_url" => "https://apps.apple.com/us/app/spin-electric-scooters/id1241808993"],
            ["name" => SixtDataImporter::getProviderName(), "color" => "#f25a04", "url" => "https://www.sixt.com/share/#/", "android_url" => "https://play.google.com/store/apps/details?id=com.sixt.reservation&hl=pl&gl=US", "ios_url" => "https://apps.apple.com/us/app/sixt-rent-share-ride-plus/id295079411"],
            ["name" => TierDataImporter::getProviderName(), "color" => "#0E1A50", "url" => "https://www.tier.app/", "android_url" => "https://play.google.com/store/apps/details?id=com.tier.app&hl=en&gl=US", "ios_url" => "https://apps.apple.com/app/id1436140272?mt=8"],
            ["name" => VoiDataImporter::getProviderName(), "color" => "#f46c63", "url" => "https://www.voiscooters.com/", "android_url" => "https://play.google.com/store/apps/details?id=io.voiapp.voi&hl=pl&gl=US", "ios_url" => "https://apps.apple.com/us/app/voi-e-scooter-e-bike-hire/id1395921017?mt=8"],
            ["name" => UrentDataImporter::getProviderName(), "color" => "#9400FF", "url" => "https://urent.ru/", "android_url" => "https://play.google.com/store/apps/details?id=ru.urentbike.app", "ios_url" => "https://apps.apple.com/gb/app/urent-e-scooters-and-bikes/id1352346712"],
            ["name" => ZwingsDataImporter::getProviderName(), "color" => "#abb8c3", "url" => "https://www.zwings.co.uk/", "android_url" => "https://play.google.com/store/apps/details?id=com.zwings", "ios_url" => "https://apps.apple.com/us/app/zwings/id1503019815"],
            ["name" => RydeDataImporter::getProviderName(), "color" => "#4dcb1f", "url" => "https://www.ryde-technology.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.ryde_android&hl=no", "ios_url" => "https://apps.apple.com/no/app/id1495605028"],
            ["name" => VeoDataImporter::getProviderName(), "color" => "#000000", "url" => "https://www.veoride.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.pgt.veoride", "ios_url" => "https://apps.apple.com/us/app/veoride/id1279820696"],
            ["name" => WindDataImporter::getProviderName(), "color" => "#fffa00", "url" => "https://www.wind.co/", "android_url" => "https://play.google.com/store/apps/details?id=com.zen.zbike&referrer=utm_source%3Dofficialweb%26utm_medium%3Dother%26utm_term%3Dglobal", "ios_url" => "https://apps.apple.com/cn/app/yango-wind-e-scooter-sharing/id1247826304?utm_source=officialweb&utm_medium=other&time=1703025387273&utm_term=global"],
            ["name" => WheeMoveDataImporter::getProviderName(), "color" => "#31682d", "url" => "https://www.wheemove.com/", "android_url" => "https://play.google.com/store/apps/details?id=com.whee.android", "ios_url" => "https://apps.apple.com/us/app/whee-e-scooter-sharing/id1465111214?l=es&ls=1"],
            ["name" => "OpenAI", "color" => "#ffffff", "url" => "https://openai.com"],
        ];

        foreach ($providers as $provider) {
            Provider::create([
                "name" => $provider["name"],
                "color" => $provider["color"],
                "url" => $provider["url"] ?? null,
                "android_url" => $provider["android_url"] ?? null,
                "ios_url" => $provider["ios_url"] ?? null,
            ]);

            $imageName = strtolower($provider["name"]) . ".png";
            $imagePath = resource_path("providers/" . $imageName);
            $newImagePath = "public/providers/" . $imageName;

            if (file_exists($imagePath) && !Storage::exists($newImagePath)) {
                Storage::put($newImagePath, file_get_contents($imagePath));
            }
        }

        if (file_exists(resource_path("providers/unknown.png")) && !Storage::exists( "public/providers/unknown.png")) {
            Storage::put("public/providers/unknown.png", file_get_contents(resource_path("providers/unknown.png")));
        }
    }
}
