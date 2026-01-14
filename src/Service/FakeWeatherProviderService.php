<?php

declare(strict_types=1);

namespace App\Service;

final class FakeWeatherProviderService implements WeatherProviderInterface
{
    private array $temperatures = [
        'Sofia' => 4,
        'London' => 8,
        'Berlin' => 6,
        'New York' => 10,
        'Tokyo' => 12,
    ];

    public function getCurrentTemperature(string $city): int
    {
        // Return deterministic value if city known, else random between -5 and 35
        return $this->temperatures[$city] ?? rand(-5, 35);
    }
}
