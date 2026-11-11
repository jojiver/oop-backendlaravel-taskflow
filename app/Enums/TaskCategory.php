<?php

namespace App\Enums;

enum TaskCategory: string
{
    case School = 'school';
    case Personal = 'personal';
    case Work = 'work';
    case Others = 'others';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
