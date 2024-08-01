<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Domain;

use App\User\Domain\FicoCalculator;
use Generator;
use PHPUnit\Framework\TestCase;

class FicoCalculatorTest extends TestCase
{
    /**
     * @dataProvider calculationData
     */
    public function testCalculation(int $userAge, string $city, int $result): void
    {
        // Arrange
        $ficoCalculator = new FicoCalculator();

        // Act
        $result = $ficoCalculator->calculate($userAge, $city);

        // Assert
        $this->assertEquals($result, $result);
    }

    /**
     * @return Generator
     */
    public function calculationData(): iterable
    {
        yield [30, 'New York', 300];
        yield [18, 'Los Angeles', 200];
        yield [40, 'Chicago', 300];
        yield [50, 'Houston', 400];
        yield [60, 'Phoenix', 500];
        yield [29, 'Illinois', 100];
        yield [14, 'Phoenix', 0];
    }
}