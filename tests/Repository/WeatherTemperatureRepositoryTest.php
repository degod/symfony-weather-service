<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Repository\WeatherTemperatureRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Doctrine\ORM\Tools\SchemaTool;

final class WeatherTemperatureRepositoryTest extends KernelTestCase
{
    private WeatherTemperatureRepository $repository;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = self::getContainer();
        $this->repository = $container->get(WeatherTemperatureRepository::class);

        // Get the entity manager and build the schema for this specific test run
        $entityManager = $container->get('doctrine.orm.entity_manager');
        $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
        
        if (!empty($metadatas)) {
            $schemaTool = new SchemaTool($entityManager);
            $schemaTool->createSchema($metadatas);
        }
    }

    public function test_saves_only_one_temperature_per_day(): void
    {
        $this->repository->saveDailyTemperature('Sofia', 4);
        $this->repository->saveDailyTemperature('Sofia', 7);

        $temps = $this->repository->getLastTenDaysTemperatures('Sofia');

        self::assertCount(1, $temps);
        self::assertSame(4, $temps[0]);
    }
}
