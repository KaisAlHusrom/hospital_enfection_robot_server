<?php

namespace App\Enum;

enum Role: string
{
    case Admin = 'admin';
    case User = 'user';
    case Owner = 'owner';

    public static function values(): array
    {
        return array_map(fn($value) => $value->value, self::cases());
    }
}
