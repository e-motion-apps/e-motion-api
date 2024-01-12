<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeInFavoriteCity extends Notification
{
    use Queueable;

    /** Create a new notification instance. */
    private string $city;

    private string $provider;
    private string $change;
    private string $url;

    public function __construct($city, $provider, $change)
    {
        $this->city = $city;
        $this->provider = $provider;
        $this->change = $change;
        $this->url = env("APP_URL");
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ["mail"];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line("There has been a change in your favorite city.")
            ->line("$this->provider has been $this->change $this->city")
            ->action("Learn more", url($this->url));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
        ];
    }
}
