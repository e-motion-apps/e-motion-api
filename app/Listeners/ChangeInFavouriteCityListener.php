<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Mail\ChangeInFavourites;
use App\Models\City;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class ChangeInFavouriteCityListener
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $users = User::query()->whereHas("favorites", function ($query) use ($event): void {
            $query->where("city_id", $event->city_id);
        })->get();
        $city_name = City::query()->where("id", $event->city_id)->first()->name;

        foreach ($users as $user) {
            Mail::to($user->email)->send(new ChangeInFavourites($city_name, $event->provider_name, $event->change));
        }
    }
}
