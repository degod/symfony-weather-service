<?php

declare(strict_types=1);

namespace App\Service;

use Psr\Cache\CacheItemPoolInterface;

final class CachedWeatherProviderService implements WeatherProviderInterface
{
    private WeatherProviderInterface $decoratedProvider;
    private CacheItemPoolInterface $cache;
    private int $ttl;

    /**
     * @param WeatherProviderInterface $decoratedProvider The actual provider (e.g., Fake or Real)
     * @param CacheItemPoolInterface   $cache             PSR-6 cache pool (Redis)
     * @param int                      $ttl               Cache duration in seconds (default 3600s = 1 hour)
     */
    public function __construct(
        WeatherProviderInterface $decoratedProvider,
        CacheItemPoolInterface $cache,
        int $ttl = 3600
    ) {
        $this->decoratedProvider = $decoratedProvider;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function getCurrentTemperature(string $city): int
    {
        $cacheKey = 'weather.current.' . strtolower($city);
        $item = $this->cache->getItem($cacheKey);

        if ($item->isHit()) {
            return $item->get();
        }

        // Fetch from the real provider
        $temperature = $this->decoratedProvider->getCurrentTemperature($city);

        // Store in cache
        $item->set($temperature);
        $item->expiresAfter($this->ttl);
        $this->cache->save($item);

        return $temperature;
    }
}
