<?php

namespace App\Helpers;

class StringSanitizer
{
    public static function sanitizeString(string $stringToSanitize): string
    {
        return preg_replace('/\s+/', '', $stringToSanitize);
    }
}
