<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\TestNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    public function testNotificationsAreSent(): void
    {
        $user = User::factory()->create();
        Notification::fake();

        Notification::assertNothingSent();
        $notification = new TestNotification();
        $user->notify($notification);
        Notification::assertSentTimes(TestNotification::class, 1);
    }
}
