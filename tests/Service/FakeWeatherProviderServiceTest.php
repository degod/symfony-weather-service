<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\FakeWeatherProviderService;
use PHPUnit\Framework\TestCase;

final class FakeWeatherProviderServiceTest extends TestCase
{
    private FakeWeatherProviderService $provider;

    protected function setUp(): void
    {
        $this->provider = new FakeWeatherProviderService();
    }

    public function test_returns_known_city_temperature(): void
    {
        $temp = $this->provider->getCurrentTemperature('Sofia');
        self::assertSame(4, $temp);
    }

    public function test_returns_random_temperature_for_unknown_city(): void
    {
        $temp = $this->provider->getCurrentTemperature('UnknownCity');
        self::assertIsInt($temp);
        self::assertGreaterThanOrEqual(-5, $temp);
        self::assertLessThanOrEqual(35, $temp);
    }
}
