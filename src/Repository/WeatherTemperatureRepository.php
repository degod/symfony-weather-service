<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\WeatherTemperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<WeatherTemperature>
 */
class WeatherTemperatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WeatherTemperature::class);
    }

    public function saveDailyTemperature(string $city, int $temperature): void
    {
        $date = new \DateTimeImmutable('today');

        $existing = $this->findOneBy([
            'city' => strtolower($city),
            'date' => $date,
        ]);

        if ($existing !== null) {
            return;
        }

        $entity = new WeatherTemperature($city, $temperature, $date);
         
        $em = $this->getEntityManager();
        $em->persist($entity);
        $em->flush();
    }

    /**
     * @return int[]
     */
    public function getLastTenDaysTemperatures(string $city): array
    {
        return array_map(
            static fn (WeatherTemperature $wt) => $wt->getTemperature(),
            $this->createQueryBuilder('w')
                ->where('w.city = :city')
                ->setParameter('city', strtolower($city))
                ->orderBy('w.date', 'DESC')
                ->setMaxResults(10)
                ->getQuery()
                ->getResult()
        );
    }

    public function deleteOlderThanTenDays(string $city): void
    {
        $cutoff = new \DateTimeImmutable('-10 days');

        $this->createQueryBuilder('w')
            ->delete()
            ->where('w.city = :city')
            ->andWhere('w.date < :cutoff')
            ->setParameter('city', strtolower($city))
            ->setParameter('cutoff', $cutoff)
            ->getQuery()
            ->execute();
    }
}
