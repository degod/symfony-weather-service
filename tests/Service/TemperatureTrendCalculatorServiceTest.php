<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Enum\TemperatureTrendEnum;
use App\Service\TemperatureTrendCalculatorService;
use PHPUnit\Framework\TestCase;

final class TemperatureTrendCalculatorServiceTest extends TestCase
{
    private TemperatureTrendCalculatorService $service;

    protected function setUp(): void
    {
        $this->service = new TemperatureTrendCalculatorService();
    }

    public function test_returns_hotter_when_current_is_above_average(): void
    {
        $trend = $this->service->calculate(
            currentTemperature: 10,
            lastTenDaysTemperatures: [5, 6, 7, 6, 5, 6, 7, 6, 5, 6]
        );

        self::assertSame(TemperatureTrendEnum::HOTTER, $trend);
    }

    public function test_returns_colder_when_current_is_below_average(): void
    {
        $trend = $this->service->calculate(
            currentTemperature: 3,
            lastTenDaysTemperatures: [5, 6, 7, 6, 5, 6, 7, 6, 5, 6]
        );

        self::assertSame(TemperatureTrendEnum::COLDER, $trend);
    }

    public function test_returns_same_when_current_equals_average(): void
    {
        $trend = $this->service->calculate(
            currentTemperature: 5,
            lastTenDaysTemperatures: [5, 5, 5, 5, 5, 5, 5, 5, 5, 5]
        );

        self::assertSame(TemperatureTrendEnum::SAME, $trend);
    }

    public function test_returns_same_when_no_history_exists(): void
    {
        $trend = $this->service->calculate(
            currentTemperature: 5,
            lastTenDaysTemperatures: []
        );

        self::assertSame(TemperatureTrendEnum::SAME, $trend);
    }
}
