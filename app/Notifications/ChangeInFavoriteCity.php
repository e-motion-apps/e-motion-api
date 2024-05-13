<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\ChangeInFavoriteCityEnum;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeInFavoriteCity extends Notification
{
    use Queueable;

    public function __construct(
        private string $cityName,
        private string $countryName,
        private string $provider,
        private ChangeInFavoriteCityEnum $change,
        private ?string $url = null,
    ) {
        $this->url = $url ?? config("app.url");
        $this->url .= "/" . strtolower($this->countryName) . "/" . strtolower($this->cityName);
    }

    public function via(User $notifiable): array
    {
        return ["mail"];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $change_string = $this->change->value;

        return (new MailMessage())
            ->subject(__("Change in favorite city"))
            ->greeting(__("Hello!"))
            ->line(__("There has been a change in your favorite city."))
            ->line($this->provider . " " . __("has been") . " " . __($change_string) . " " . $this->cityName . ".")
            ->action(__("Learn more"), url($this->url))
            ->salutation(__("Thank you for using our application!"));
    }
}
