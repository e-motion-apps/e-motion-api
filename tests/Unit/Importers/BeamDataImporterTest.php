<?php

declare(strict_types=1);

namespace Tests\Unit\Importers;

use App\Importers\BeamDataImporter;
use App\Services\MapboxGeocodingService;
use Database\Seeders\ProviderSeeder;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;

class BeamDataImporterTest extends TestCase
{
    private $dataImporter;

    protected function setUp(): void
    {
//        to fix provider - whole Indonesia, Japan, partially Turkiye
        parent::setUp();
        $this->seed(ProviderSeeder::class);
        $mockResponseBody = '<div class="find-beam-box"><div class="slider-arrow-wrapper"><div class="d-flex-space-between-wrap"><div><a href="#" class="findbeam-prev-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d5ba81b9917_icon-previous-active%402x.webp" loading="lazy" alt="prev" class="h-24"></a><a href="#" class="findbeam-next-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d60541b9916_icon-next-active%402x.webp" loading="lazy" alt="next" class="h-24"></a></div><div><a href="#" class="beam-map-close w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d06d01b9b43_bm-close-icon%402x.webp" loading="lazy" alt="close" class="h-24"></a></div></div></div><h4 class="find-beam-title-map">Türkiye</h4><p class="find-beam-launch-date">Launched in <strong>June 2022</strong></p><div class="beam-col-main-box"><div class="beam-col col-w-full"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png" loading="lazy" alt="scooter" class="h-34 mb-10"><p class="text-14px">Ankara<br>Antalya<br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;• </strong>Alanya</span><br>Muğla<br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;• </strong>Bodrum</span><br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;• </strong>Fethiye</span><br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;&nbsp;• </strong>Marmaris</span><br></p></div></div><a href="https://ride-beam-v2.webflow.io/download-app" class="button find-beam-btn w-button">Find a Beam near you</a></div>';
//       indonesia beam-box
//        $mockResponseBody = '<div class="find-beam-box"><div class="slider-arrow-wrapper"><div class="d-flex-space-between-wrap"><div><a href="#" class="findbeam-prev-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d5ba81b9917_icon-previous-active%402x.webp" loading="lazy" alt="prev" class="h-24"></a><a href="#" class="findbeam-next-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d60541b9916_icon-next-active%402x.webp" loading="lazy" alt="next" class="h-24"></a></div><div><a href="#" class="beam-map-close w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d06d01b9b43_bm-close-icon%402x.webp" loading="lazy" alt="close" class="h-24"></a></div></div></div><h4 class="find-beam-title-map">Indonesia</h4><p class="find-beam-launch-date">Launched in <strong>September 2022</strong></p><div class="beam-col-main-box"><div class="beam-col col-w-full"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b68e897e10ec0154c2_map-id-vehicle-saturn.png" loading="lazy" alt="scooter" class="h-34 mb-10 mr-6"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b6bd85e2545d2a9093_map-id-vehicle-saturn-apollo.png" loading="lazy" alt="scooter" class="h-34 mb-10"><p class="text-14px">Bali<br>Bogor<br>Cikarang<br>Tangerang Selatan<br></p></div></div><a href="https://ride-beam-v2.webflow.io/download-app" class="button find-beam-btn w-button">Find a Beam near you</a></div>';
//        japan beam-box
//        $mockResponseBody = '<div class="find-beam-box"><div class="slider-arrow-wrapper"><div class="d-flex-space-between-wrap"><div><a href="#" class="findbeam-prev-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d5ba81b9917_icon-previous-active%402x.webp" loading="lazy" alt="prev" class="h-24"></a><a href="#" class="findbeam-next-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d60541b9916_icon-next-active%402x.webp" loading="lazy" alt="next" class="h-24"></a></div><div><a href="#" class="beam-map-close w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d06d01b9b43_bm-close-icon%402x.webp" loading="lazy" alt="close" class="h-24"></a></div></div></div><h4 class="find-beam-title-map">Japan</h4><p class="find-beam-launch-date">Launched in <strong>October 2022</strong></p><div class="beam-col-main-box"><div class="beam-col col-w-full"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png" loading="lazy" alt="scooter" class="h-34 mb-10"><p class="text-14px">Osaka Prefecture 大阪府<br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;• </strong>Osaka City 大阪市<br></span><span>Niigata Prefecture 新潟県</span><br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;&nbsp;• </strong>Minami Uonuma City 南魚沼市</span><br></p></div></div><a href="https://ride-beam-v2.webflow.io/download-app" class="button find-beam-btn w-button">Find a Beam near you</a></div>';

        $mockHandler = new MockHandler([
            new Response(200, [], $mockResponseBody),
        ]);

        $handlerStack = HandlerStack::create($mockHandler);
        $mockHttpClient = new Client(["handler" => $handlerStack]);

//        $mockGoogleTranslate = $this->createMock(GoogleTranslate::class);

        $mockMapboxService = $this->createMock(MapboxGeocodingService::class);
        $mockMapboxService->method("getCoordinatesFromApi")
            ->willReturn([
                "latitude" => "mocked_latitude",
                "longitude" => "mocked_longitude",
            ]);

        $this->dataImporter = new class($mockHttpClient, $mockMapboxService) extends BeamDataImporter {
            protected function createImportInfoDetails($code, $providerName): void
            {
            }
        };
    }

    public function testAddAllRecordsToDatabase(): void
    {
        $this->dataImporter->extract();
        $this->dataImporter->transform();

        $this->assertDatabaseHas("city_without_assigned_countries", [

            "city_name" => "Ankara", //Muğla, Antalya
            "country_name" => "Türkiye",
        ]);

        $this->assertDatabaseCount('city_without_assigned_countries', 1);
    }
}
