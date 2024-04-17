<?php

declare(strict_types=1);

namespace App\Enums;

enum ChangeInFavouriteCityEnum: string
{
    case Added = "added_to";
    case Removed = "removed_from";
}
