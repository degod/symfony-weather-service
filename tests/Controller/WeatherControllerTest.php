<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\Repository\WeatherTemperatureRepository;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class WeatherControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private WeatherTemperatureRepository $repository;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        
        $container = static::getContainer();
        $this->repository = $container->get(WeatherTemperatureRepository::class);

        $entityManager = $container->get('doctrine.orm.entity_manager');
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        
        if (!empty($metadatas)) {
            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->dropSchema($metadatas);
            $schemaTool->createSchema($metadatas);
        }
    }

    public function test_missing_city_returns_400(): void
    {
        $this->client->request('GET', '/api/weather');
        self::assertResponseStatusCodeSame(400);
    }

    public function test_weather_endpoint_returns_success(): void
    {
        $this->repository->saveDailyTemperature('Sofia', 15);

        $this->client->request('GET', '/api/weather?city=Sofia');

        self::assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);

        self::assertArrayHasKey('city', $data);
        self::assertSame('sofia', strtolower($data['city']));
        self::assertArrayHasKey('average_last_10_days', $data);
    }
}
