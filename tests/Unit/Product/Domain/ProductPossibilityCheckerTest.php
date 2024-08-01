<?php

declare(strict_types=1);

namespace App\Tests\Unit\Product\Domain;

use App\Product\Domain\Policy;
use App\Product\Domain\ProductPossibilityChecker;
use Generator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ProductPossibilityCheckerTest extends TestCase
{
    /**
     * @param Policy[] $policies
     * @dataProvider providePossibility
     */
    public function testCheckPossibility(
        array $policies,
        int $fico,
        int $income,
        int $age,
        string $state,
        bool $expectedResult
    ): void
    {
        // Arrange
        $possibilityChecker = new ProductPossibilityChecker();

        // Act
        $result = $possibilityChecker->check($policies, $fico, $income, $age, $state);

        // Assert
        self::assertEquals($expectedResult, $result);
    }

    /**
     * @return Generator
     */
    public function providePossibility(): iterable
    {
        // check 2 policies
        yield [
            [
                new Policy(
                    id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_FICO,
                    operator: '>',
                    amount: 700,
                ),
                new Policy(
                    id: Uuid::fromString('22222222-2222-2222-2222-222222222222'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_ANNUAL_INCOME,
                    operator: '>',
                    amount: 100000,
                ),
            ],
            800,
            120000,
            35,
            'CA',
            true,
        ];
        // check fico
        yield [
            [
                new Policy(
                    id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_FICO,
                    operator: '>',
                    amount: 700,
                ),
            ],
            800,
            120000,
            35,
            'CA',
            true,
        ];
        // check one age policy
        yield [
            [
                new Policy(
                    id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_AGE,
                    operator: '>',
                    amount: 30,
                ),
            ],
            800,
            120000,
            35,
            'CA',
            true,
        ];
        // check one income policy
        yield [
            [
                new Policy(
                    id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_ANNUAL_INCOME,
                    operator: '>',
                    amount: 100000,
                ),
            ],
            800,
            120000,
            35,
            'CA',
            true,
        ];
        // check IN_ARRAY state policy
        yield [
            [
                new Policy(
                    id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_STATE,
                    operator: 'IN_ARRAY',
                    arrayValues: ['CA', 'NY'],
                ),
            ],
            800,
            120000,
            35,
            'CA',
            true,
        ];
        // check <> state policy
        yield [
            [
                new Policy(
                    id: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    productId: Uuid::fromString('11111111-1111-1111-1111-111111111111'),
                    field: Policy::FIELD_STATE,
                    operator: '<>',
                    stringValue: 'CA',
                    probability: 0,
                ),
            ],
            800,
            120000,
            35,
            'CA',
            false,
        ];
    }
}