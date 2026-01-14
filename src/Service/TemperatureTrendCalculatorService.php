<?php

declare(strict_types=1);

namespace App\Service;

use App\Enum\TemperatureTrendEnum;

final class TemperatureTrendCalculatorService
{
    /**
     * @param int   $currentTemperature
     * @param int[] $lastTenDaysTemperatures
     */
    public function calculate(
        int $currentTemperature,
        array $lastTenDaysTemperatures
    ): TemperatureTrendEnum {
        if (count($lastTenDaysTemperatures) === 0) {
            return TemperatureTrendEnum::SAME;
        }

        $average = array_sum($lastTenDaysTemperatures) / count($lastTenDaysTemperatures);

        return match (true) {
            $currentTemperature > $average => TemperatureTrendEnum::HOTTER,
            $currentTemperature < $average => TemperatureTrendEnum::COLDER,
            default => TemperatureTrendEnum::SAME,
        };
    }
}
