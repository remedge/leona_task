<?php

declare(strict_types=1);

namespace App\Product\Domain;

class ProductPossibilityChecker
{
    /**
     * @param Policy[] $policies
     */
    public function check(array $policies, int $fico, int $income, int $age, string $state): bool
    {

        $checkResults = [];
        foreach ($policies as $policy) {
            $checkResults[] = match ($policy->getField()) {
                Policy::FIELD_FICO => match ($policy->getOperator()) {
                    '>' => $fico > $policy->getAmount(),
                    '<' => $fico < $policy->getAmount(),
                    default => false,
                },
                Policy::FIELD_ANNUAL_INCOME => match ($policy->getOperator()) {
                    '>' => $income > $policy->getAmount(),
                    '<' => $income < $policy->getAmount(),
                    '>=' => $income >= $policy->getAmount(),
                    '<=' => $income <= $policy->getAmount(),
                    default => false,
                },
                Policy::FIELD_AGE => match ($policy->getOperator()) {
                    '>' => $age > $policy->getAmount(),
                    '<' => $age < $policy->getAmount(),
                    '>=' => $age >= $policy->getAmount(),
                    '<=' => $age <= $policy->getAmount(),
                    default => false,
                },
                Policy::FIELD_STATE => match ($policy->getOperator()) {
                    'IN_ARRAY' => in_array($state, $policy->getArrayValues()),
                    '<>' => ($state !== $policy->getStringValue() || (mt_rand(0, 100) < $policy->getProbability())),
                    default => false,
                },
                default => false,
            };
        }

        if (in_array(false, $checkResults, true)) {
            return false;
        }

        return true;
    }
}