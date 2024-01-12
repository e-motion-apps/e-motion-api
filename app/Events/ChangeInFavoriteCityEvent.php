<?php

declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeInFavoriteCityEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public int $city_id;

    public string $provider_name;
    public string $change;

    public function __construct($city_id, $provider_name, $change)
    {
        $this->city_id = $city_id;
        $this->provider_name = $provider_name;
        $this->change = $change;
    }


    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("channel-name"),
        ];
    }
}
