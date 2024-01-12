<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\City;
use App\Models\User;
use App\Notifications\ChangeInFavoriteCity;
use Illuminate\Support\Facades\Notification;

class ChangeInFavoriteCityListener
{

    public function __construct() {}

    public function handle(object $event): void
    {
        $users = User::query()->whereHas("favorites", function ($query) use ($event): void {
            $query->where("city_id", $event->city_id);
        })->get();
        $city_name = City::query()->where("id", $event->city_id)->first()->name;

        Notification::send($users, new ChangeInFavoriteCity($city_name, $event->provider_name, $event->change));
    }
}
