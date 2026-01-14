<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\CityWeatherDTO;
use App\Repository\WeatherTemperatureRepository;

final class GetCityWeatherService
{
    public function __construct(
        private readonly WeatherProviderInterface $weatherProvider,
        private readonly WeatherTemperatureRepository $repository,
        private readonly TemperatureTrendCalculatorService $trendCalculator
    ) {}

    public function execute(string $city): CityWeatherDTO
    {
        $temperature = $this->weatherProvider->getCurrentTemperature($city);

        // Persist today's temperature
        $this->repository->saveDailyTemperature($city, $temperature);
        $this->repository->deleteOlderThanTenDays($city);

        $history = $this->repository->getLastTenDaysTemperatures($city);

        $trend = $this->trendCalculator->calculate($temperature, $history);
        $average = count($history) > 0
            ? array_sum($history) / count($history)
            : $temperature;

        return new CityWeatherDTO(
            city: $city,
            temperature: $temperature,
            trend: $trend,
            averageLastTenDays: $average
        );
    }
}
