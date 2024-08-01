<?php

declare(strict_types=1);

namespace App\Tests\Unit\Finance\Application\Query;

use App\Finance\Application\Query\IncomeQuery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class IncomeQueryTest extends TestCase
{
    public function testGetMonthlyIncomeByUser(): void
    {
        // Arrange
        $userId = Uuid::fromString('11111111-1111-1111-1111-111111111111');
        $incomeQuery = new IncomeQuery();

        // Act
        $result = $incomeQuery->getMonthlyIncomeByUser($userId);

        // Assert
        $this->assertEquals(1000, $result);
    }
}