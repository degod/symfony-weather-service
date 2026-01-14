<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\CityWeatherDTO;
use App\Enum\TemperatureTrendEnum;
use App\Repository\WeatherTemperatureRepository;
use App\Service\GetCityWeatherService;
use App\Service\TemperatureTrendCalculatorService;
use App\Service\WeatherProviderInterface;
use PHPUnit\Framework\TestCase;

final class GetCityWeatherServiceTest extends TestCase
{
    public function test_happy_path(): void
    {
        $provider = $this->createMock(WeatherProviderInterface::class);
        $repository = $this->createMock(WeatherTemperatureRepository::class);
        $calculator = new TemperatureTrendCalculatorService();

        $provider->method('getCurrentTemperature')->with('Sofia')->willReturn(10);

        $repository->expects(self::once())->method('saveDailyTemperature');
        $repository->expects(self::once())->method('deleteOlderThanTenDays');
        $repository->method('getLastTenDaysTemperatures')->willReturn([5, 6, 7, 8, 9]);

        $service = new GetCityWeatherService($provider, $repository, $calculator);
        $result = $service->execute('Sofia');

        self::assertInstanceOf(CityWeatherDTO::class, $result);
        self::assertSame('Sofia', $result->city);
        self::assertSame(10, $result->temperature);
        self::assertSame(TemperatureTrendEnum::HOTTER, $result->trend);
        self::assertSame(7.0, $result->averageLastTenDays);
    }
}
