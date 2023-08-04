<?php

declare(strict_types=1);

namespace App\Helpers;

class I18n
{
    /**
     * @throws JsonException
     */
    public static function getTranslations(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }

        return json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    }
}
