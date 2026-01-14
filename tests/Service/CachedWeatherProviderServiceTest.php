<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Service\CachedWeatherProviderService;
use App\Service\WeatherProviderInterface;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class CachedWeatherProviderServiceTest extends TestCase
{
    public function test_returns_cached_temperature_if_hit(): void
    {
        $city = 'Sofia';

        $itemMock = $this->createMock(CacheItemInterface::class);
        $itemMock->expects(self::once())->method('isHit')->willReturn(true);
        $itemMock->expects(self::once())->method('get')->willReturn(4);

        $cacheMock = $this->createMock(CacheItemPoolInterface::class);
        $cacheMock->expects(self::once())->method('getItem')->with('weather.current.sofia')->willReturn($itemMock);

        $decoratedMock = $this->createMock(WeatherProviderInterface::class);
        $decoratedMock->expects(self::never())->method('getCurrentTemperature');

        $service = new CachedWeatherProviderService($decoratedMock, $cacheMock);
        $temp = $service->getCurrentTemperature($city);

        self::assertSame(4, $temp);
    }

    public function test_fetches_and_caches_if_miss(): void
    {
        $city = 'Sofia';

        $itemMock = $this->createMock(CacheItemInterface::class);
        $itemMock->expects(self::once())->method('isHit')->willReturn(false);
        $itemMock->expects(self::once())->method('set')->with(4);
        $itemMock->expects(self::once())->method('expiresAfter')->with(3600);

        $cacheMock = $this->createMock(CacheItemPoolInterface::class);
        $cacheMock->expects(self::once())->method('getItem')->with('weather.current.sofia')->willReturn($itemMock);
        $cacheMock->expects(self::once())->method('save')->with($itemMock);

        $decoratedMock = $this->createMock(WeatherProviderInterface::class);
        $decoratedMock->expects(self::once())->method('getCurrentTemperature')->with($city)->willReturn(4);

        $service = new CachedWeatherProviderService($decoratedMock, $cacheMock);
        $temp = $service->getCurrentTemperature($city);

        self::assertSame(4, $temp);
    }
}
