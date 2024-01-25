<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\ChangeInFavouriteCityEnum;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeInFavoriteCity extends Notification
{
    use Queueable;

    public function __construct(
        private string $city,
        private string $provider,
        private ChangeInFavouriteCityEnum $change,
        private ?string $url = null,
    ) {
        $this->url = $url ?? config("app.url");
    }

    public function via(object $notifiable): array
    {
        return ["mail"];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $change_string = $this->change->value;

        return (new MailMessage())
            ->line("There has been a change in your favorite city.")
            ->line("$this->provider has been $change_string $this->city")
            ->action("Learn more", url($this->url));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
