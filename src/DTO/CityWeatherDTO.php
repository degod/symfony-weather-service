<?php

declare(strict_types=1);

namespace App\DTO;

use App\Enum\TemperatureTrendEnum;

final class CityWeatherDTO
{
    public function __construct(
        public readonly string $city,
        public readonly int $temperature,
        public readonly TemperatureTrendEnum $trend,
        public readonly float $averageLastTenDays
    ) {}
}
