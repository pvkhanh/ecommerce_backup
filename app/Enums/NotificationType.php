<?php

namespace App\Enums;

enum NotificationType: string
{
    case System = 'system';
    case Order = 'order';
    case Promotion = 'promotion';
    case Activity = 'activity';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}