<?php

declare(strict_types=1);

use App\Http\Controllers\Admin\CityAlternativeNameController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Admin\ImportInfoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChangeLocaleController;
use App\Http\Controllers\CityOpinionController;
use App\Http\Controllers\CityPageController;
use App\Http\Controllers\CityProviderController;
use App\Http\Controllers\CityWithoutAssignedCountryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FavoritesController;
use Illuminate\Support\Facades\Route;

Route::middleware("guest")->group(function (): void {
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::post("/register", [AuthController::class, "store"])->name("register");

    Route::get("/login/{provider}", [AuthController::class, "redirectToProvider"])->name("login.provider");
    Route::get("/login/{provider}/redirect", [AuthController::class, "handleProviderRedirect"]);
});

Route::middleware("auth")->group(function (): void {
    Route::post("/logout", [AuthController::class, "logout"])->name("logout");

    Route::post("/favorites", [FavoritesController::class, "store"]);
    Route::get("/favorites/{city_id}", [FavoritesController::class, "check"]);
    Route::get("/favorite-cities", [FavoritesController::class, "index"]);

    Route::post("/opinions", [CityOpinionController::class, "store"]);
    Route::patch("/opinions/{cityOpinion}", [CityOpinionController::class, "update"]);
    Route::delete("/opinions/{cityOpinion}", [CityOpinionController::class, "destroy"]);

    Route::middleware(["role:admin"])->group(function (): void {
        Route::get("/admin/importers", [ImportInfoController::class, "index"]);
        Route::resource("/admin/countries", CountryController::class);
        Route::resource("/admin/cities", CityController::class);
        Route::resource("/admin/dashboard", DashboardController::class);
        Route::resource("/city-alternative-name", CityAlternativeNameController::class);
        Route::patch("/update-city-providers/{city}", [CityProviderController::class, "update"]);

        Route::post("/run-importers", [CityProviderController::class, "runImporters"]);
        Route::delete("/delete-city-without-assigned-country/{city}", [CityWithoutAssignedCountryController::class, "destroy"]);
        Route::post("/delete-all-cities-without-assigned-country", [CityWithoutAssignedCountryController::class, "destroyAll"]);
    });
});

Route::post("/language/{locale}", ChangeLocaleController::class);

Route::get('/test', function () {

    $html = file_get_contents("https://partner.ridebeam.com/cities");
    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
    $sections = $crawler->filter("div.find-beam-box"); // Remove the space after "div"
    $url = "https://uploads-ssl.webflow.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png";

    foreach ($sections as $section) {
        echo $section->html() . PHP_EOL;
    }
// $section = "<div class="slider-arrow-wrapper"><div class="d-flex-space-between-wrap"><div><a href="#" class="findbeam-prev-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d5ba81b9917_icon-previous-active%402x.webp" loading="lazy" alt="prev" class="h-24"></a><a href="#" class="findbeam-next-button w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d60541b9916_icon-next-active%402x.webp" loading="lazy" alt="next" class="h-24"></a></div><div><a href="#" class="beam-map-close w-inline-block"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63c4acbedbab5d06d01b9b43_bm-close-icon%402x.webp" loading="lazy" alt="close" class="h-24"></a></div></div></div><h4 class="find-beam-title-map">Japan</h4><p class="find-beam-launch-date">Launched in <strong>October 2022</strong></p><div class="beam-col-main-box"><div class="beam-col col-w-full"><img src="https://assets-global.website-files.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png" loading="lazy" alt="scooter" class="h-34 mb-10"><p class="text-14px">Osaka Prefecture 大阪府<br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;• </strong>Osaka City 大阪市<br></span><span>Niigata Prefecture 新潟県</span><br><span class="color-lightest-violet ml-left-5"><strong>&nbsp;&nbsp;• </strong>Minami Uonuma City 南魚沼市</span><br></p></div></div><a href="https://ride-beam-v2.webflow.io/download-app" class="button find-beam-btn w-button">Find a Beam near you</a>";
//    $html = file_get_contents("https://partner.ridebeam.com/cities");
//    $crawler = new \Symfony\Component\DomCrawler\Crawler($html);
//    $sections = $crawler->filter("div .find-beam-box");
    $url = "https://uploads-ssl.webflow.com/63c4acbedbab5dea8b1b98cd/63d8a5b60da91e7d71298637_map-vehicle-saturn.png";

//    foreach ($sections as $section) {
//        echo $section;
        foreach ($section->childNodes as $node) {
            if ($node->nodeName === "h4") {
                $countryName = $node->nodeValue;
            }
//            if ($node->nodeName === "div") {
//                foreach ($node->childNodes as $div) {
//                    if ($div->nodeName === "div") {
//                        foreach ($div->childNodes as $city) {
//                            if ($city->nodeName === "img" && $city->getAttribute("src") === $url) {
//                                $hasEscooters = true;
//                            } elseif ($city->nodeName === "img" && $city->getAttribute("src") !== $url) {
//                                $hasEscooters = false;
//                            }
//                            if ($city->nodeName === "p" && $hasEscooters === true) {
//                                $search = ["\u{00A0}", "\u{200D}", "Prefecture"];
//                                $valueToDelete = "Selangor";
//                                $cityName = preg_replace('/[\p{Hiragana}\p{Katakana}\p{Han}]+/u', "", $city->nodeValue);
//                                $cityName = str_replace($search, "", $cityName);
//                                $cityName = preg_replace('/(?<=[^\s_\-])(?=[A-Z])/', "  ", $city->nodeValue);
//                                $arrayOfCitiesNames = explode("  ", $cityName);
//                                $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($value) => $value !== $valueToDelete);
//                                $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($record) => strlen($record) > 1);
//                                $arrayOfCitiesNames = array_filter($arrayOfCitiesNames, fn($record) => strpos($record, "•") === false);
//
//                                foreach ($arrayOfCitiesNames as $cityName) {
//                                    if ($cityName === "Selangor") {
//                                    } else {
//                                        $cityName = trim($cityName);
//
//                                        if ($countryName === "Korea") {
//                                            $countryName = "South Korea";
//                                        }
//                                       echo $cityName . $countryName . "<br>";
//                                        echo "działa";
//                                    }
//                                }
//                            }
//                        }
//                    }
//                }
//            }
//        }
    }
});

Route::inertia("/", "Landing/Index")->name("home");
Route::get("/{country:slug}/{city:slug}", [CityPageController::class, "index"]);
