<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\ChangeInFavoriteCityEvent;
use App\Models\City;
use App\Models\User;
use App\Notifications\ChangeInFavoriteCity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class ChangeInFavoriteCityListener
{
    public function __construct() {}

    public function handle(ChangeInFavoriteCityEvent $event): void
    {
        $users = User::query()->whereHas("favorites", function (Builder $query) use ($event): void {
            $query->where("city_id", $event->city_id);
        })->get();
        $cityName = City::query()->where("id", $event->city_id)->first()->name;
        $countryName = City::query()->where("id", $event->city_id)->first()->country->name;

        Notification::send($users, new ChangeInFavoriteCity($cityName, $countryName, $event->provider_name, $event->change));
    }
}
