<?php

declare(strict_types=1);

namespace App\Providers;

use App\Events\ChangeInFavoriteCityEvent;
use App\Listeners\ChangeInFavoriteCityListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ChangeInFavoriteCityEvent::class => [
            ChangeInFavoriteCityListener::class,
        ],
    ];

    public function boot(): void
    {
    }

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
