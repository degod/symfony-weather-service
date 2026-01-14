<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final class OpenMeteoWeatherProviderService implements WeatherProviderInterface
{
    public function __construct(
        private readonly HttpClientInterface $httpClient
    ) {}

    public function getCurrentTemperature(string $city): int
    {
        $coordinates = $this->getCoordinates($city);

        $response = $this->httpClient->request(
            'GET',
            'https://api.open-meteo.com/v1/forecast',
            [
                'query' => [
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lon'],
                    'current_weather' => true,
                ],
            ]
        );

        $data = $response->toArray();

        if (!isset($data['current_weather']['temperature'])) {
            throw new \RuntimeException('Temperature not available');
        }

        return (int) round($data['current_weather']['temperature']);
    }

    /**
     * Hardcoded for demo purposes
     */
    private function getCoordinates(string $city): array
    {
        return match (strtolower($city)) {
            'sofia' => ['lat' => 42.6977, 'lon' => 23.3219],
            'london' => ['lat' => 51.5074, 'lon' => -0.1278],
            'berlin' => ['lat' => 52.5200, 'lon' => 13.4050],
            default => ['lat' => 0.00, 'lon' => 0.00],
        };
    }
}
