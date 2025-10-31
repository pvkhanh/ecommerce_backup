<?php

namespace App\Enums;

enum UserRole: string
{
    case Buyer = 'buyer';
    case Admin = 'admin';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}