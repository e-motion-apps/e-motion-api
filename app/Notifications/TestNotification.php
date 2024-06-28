<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    public function __construct() {}

    public function via(object $notifiable): array
    {
        return ["mail"];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line("The introduction to the notification.")
            ->action("Notification Action", url("/"))
            ->line("Thank you for using our application!");
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
