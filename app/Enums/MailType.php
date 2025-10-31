<?php

namespace App\Enums;

enum MailType: string
{
    case System = 'system';
    case User = 'user';
    case Marketing = 'marketing';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}