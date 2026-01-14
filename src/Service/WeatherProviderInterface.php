<?php

declare(strict_types=1);

namespace App\Service;

interface WeatherProviderInterface
{
    /**
     * Returns the current temperature for a city in Celsius.
     *
     * @param string $city
     * @return int
     */
    public function getCurrentTemperature(string $city): int;
}
