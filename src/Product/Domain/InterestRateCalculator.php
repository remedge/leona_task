<?php

declare(strict_types=1);

namespace App\Product\Domain;

class InterestRateCalculator
{
    public function calculate(float $interestRate, string $state): float
    {
        if ($state === 'NY') {
            return $interestRate + 11.49;
        }

        return $interestRate;
    }
}