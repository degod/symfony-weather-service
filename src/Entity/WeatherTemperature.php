<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WeatherTemperatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity(repositoryClass: WeatherTemperatureRepository::class)]
#[ORM\Table(name: 'weather_temperatures')]
#[ORM\UniqueConstraint(columns: ['city', 'date'])]
class WeatherTemperature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $city;

    #[ORM\Column]
    private int $temperature;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private \DateTimeInterface $date;

    public function __construct(string $city, int $temperature, \DateTimeInterface $date)
    {
        $this->city = strtolower($city);
        $this->temperature = $temperature;
        $this->date = $date;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }
}
