<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\GetCityWeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class WeatherController extends AbstractController
{
    #[Route('/api/weather', name: 'api.weather', methods: ['GET'])]
    public function __invoke(
        Request $request,
        GetCityWeatherService $getCityWeatherService
    ): JsonResponse {
        $city = trim((string) $request->query->get('city'));

        if ($city === '') {
            return $this->json(
                ['error' => 'City query parameter is required'],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $result = $getCityWeatherService->execute($city);

        return $this->json([
            'city' => $result->city,
            'temperature' => $result->temperature . " " . $result->trend->value,
            'average_last_10_days' => $result->averageLastTenDays,
        ]);
    }
}
