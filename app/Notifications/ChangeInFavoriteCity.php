<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Enums\ChangeInFavouriteCityEnum;
use App\Models\User;
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

    public function via(User $notifiable): array
    {
        return ["mail"];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $change_string = $this->change->value;

        return (new MailMessage())
            ->subject(__("Change In Favorite City"))
            ->greeting(__("Hello!"))
            ->line(__("There has been a change in your favorite city."))
            ->line($this->provider . " " . __("has been") . " " . __($change_string) . " " . $this->city . ".")
            ->action(__("Learn more"), url($this->url))
            ->salutation(__("Thank you for using our application!"));
    }
}
