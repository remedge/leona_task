<?php

declare(strict_types=1);

namespace App\Finance\Application\Query;

use Ramsey\Uuid\UuidInterface;

class IncomeQuery
{
    public function getMonthlyIncomeByUser(UuidInterface $userId): int
    {
        return match ($userId->toString()) {
            '11111111-1111-1111-1111-111111111111' => 1000,
            '22222222-2222-2222-2222-222222222222' => 2000,
            '33333333-3333-3333-3333-333333333333' => 3000,
            '44444444-4444-4444-4444-444444444444' => 4000,
            default => 0,
        };
    }
}