<?php

namespace App\Helpers;

class StringSanitizer
{
    public static function sanitizePhone(string $phone): string
    {
        $unescaped = stripcslashes($phone);

        return preg_replace('/[^0-9+]/', '', $unescaped);
    }
}
