<?php

declare(strict_types=1);

namespace App\Enum;

enum TemperatureTrendEnum: string
{
    case HOTTER = '🥵';
    case COLDER = '🥶';
    case SAME = '-';
}