<?php

declare(strict_types=1);

namespace App\Tests\Unit\Product\Domain;

use App\Product\Domain\Policy;
use Generator;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PolicyTest extends TestCase
{
    /**
     * @param string[] $arrayValues
     * @dataProvider initialData
     */
    public function testCreate(
        string $id,
        string $productId,
        string $field,
        string $operator,
        ?int $amount,
        ?string $stringValue,
        array $arrayValues = [],
        int $probability = 100,
        ?string $exception = null
    ): void {
        // Arrange
        if ($exception !== null) {
            $this->expectExceptionMessage($exception);
        }

        $policy = new Policy(
            id: Uuid::fromString($id),
            productId: Uuid::fromString($productId),
            field: $field,
            operator: $operator,
            amount: $amount,
            stringValue: $stringValue,
            arrayValues: $arrayValues,
            probability: $probability
        );

        // Act
        $result = $policy->getId();

        // Assert
        self::assertEquals($id, $result->toString());
    }

    /**
     * @return Generator
     */
    public function initialData(): iterable
    {
        // correct data
        yield [
            'id' => '11111111-1111-1111-1111-111111111111',
            'productId' => '11111111-1111-1111-1111-111111111111',
            'field' => 'fico',
            'operator' => '>',
            'amount' => 100,
            'stringValue' => null,
            'arrayValues' => [],
            'probability' => 100,
            'exception' => null,
        ];
        // incorrect field
        yield [
            'id' => '11111111-1111-1111-1111-111111111111',
            'productId' => '11111111-1111-1111-1111-111111111111',
            'field' => 'incorrect_field',
            'operator' => '>',
            'amount' => 100,
            'stringValue' => null,
            'arrayValues' => [],
            'probability' => 100,
            'exception' => 'Unsupported field',
        ];
        // incorrect operator
        yield [
            'id' => '11111111-1111-1111-1111-111111111111',
            'productId' => '11111111-1111-1111-1111-111111111111',
            'field' => 'fico',
            'operator' => 'incorrect_operator',
            'amount' => 100,
            'stringValue' => null,
            'arrayValues' => [],
            'probability' => 100,
            'exception' => 'Unsupported operator',
        ];
    }
}