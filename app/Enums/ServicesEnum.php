<?php

declare(strict_types=1);

namespace App\Enums;

enum ServicesEnum: string
{
    case Bike = "bike";
    case Cargo = "cargo";
    case Emoped = "emoped";
    case Escooter = "escooter";
    case Motorscooter = "motorscooter";
}
