<?php

declare(strict_types=1);

namespace App\Tests\Unit\Product\Domain;

use App\Product\Domain\InterestRateCalculator;
use Generator;
use PHPUnit\Framework\TestCase;

class InterestRateCalculatorTest extends TestCase
{
    /**
     * @dataProvider calculationData
     */
    public function testCalculate(float $interestRate, string $state, float $result): void
    {
        // Arrange
        $interestRateCalculator = new InterestRateCalculator();

        // Act
        $calculationResult = $interestRateCalculator->calculate($interestRate, $state);

        // Assert
        $this->assertEquals(0, round($result - $calculationResult, 2));
    }

    /**
     * @return Generator
     */
    public function calculationData(): iterable
    {
        yield [10.0, 'NY', 21.49];
        yield [10.0, 'CA', 10.00];
    }
}