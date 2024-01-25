<?php

declare(strict_types=1);

namespace App\Enums;

enum ChangeInFavouriteCityEnum: string
{
    case Added = "added to";
    case Removed = "removed from";
}
