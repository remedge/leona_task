<?php

declare(strict_types=1);

namespace App\User\Domain;

class FicoCalculator
{
    public function calculate(int $userAge, string $city): int
    {
        $ficoScore = 0;

        if ($userAge >= 18 && $userAge <= 29) {
            $ficoScore += 100;
        } elseif ($userAge >= 30 && $userAge <= 39) {
            $ficoScore += 200;
        } elseif ($userAge >= 40 && $userAge <= 49) {
            $ficoScore += 300;
        } elseif ($userAge >= 50 && $userAge <= 59) {
            $ficoScore += 400;
        } elseif ($userAge >= 60) {
            $ficoScore += 500;
        }

        match ($city) {
            'New York' => $ficoScore += 100,
            'Los Angeles' => $ficoScore += 200,
            'Chicago' => $ficoScore += 300,
            'Houston' => $ficoScore += 400,
            'Phoenix' => $ficoScore += 500,
            default => null
        };

        return $ficoScore;
    }
}