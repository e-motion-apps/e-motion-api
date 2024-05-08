<?php

declare(strict_types=1);

namespace App\Events;

use App\Enums\ChangeInFavoriteCityEnum;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChangeInFavoriteCityEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(
        public int $city_id,
        public string $provider_name,
        public ChangeInFavoriteCityEnum $change,
    ) {}
}
