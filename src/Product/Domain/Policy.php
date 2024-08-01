<?php

declare(strict_types=1);

namespace App\Product\Domain;

use InvalidArgumentException;
use Ramsey\Uuid\UuidInterface;

class Policy
{
    public const FIELD_FICO = 'fico';

    public const FIELD_AGE = 'age';

    public const FIELD_ANNUAL_INCOME = 'annual_income';

    public const FIELD_STATE = 'state';

    public const OPERATOR_GREATER_THAN = '>';

    public const OPERATOR_LESS_THAN = '<';

    public const OPERATOR_GREATER_THAN_OR_EQUAL = '>=';

    public const OPERATOR_LESS_THAN_OR_EQUAL = '<=';

    public const OPERATOR_NOT_EQUAL = '<>';

    public const IN_ARRAY = 'IN_ARRAY';

    /**
     * @param string[] $arrayValues
     */
    public function __construct(
        private UuidInterface $id,
        private UuidInterface $productId,
        private string $field, // fico, age, annual_income, age, state
        private string $operator, // >, <, >=, <=,
        private ?int $amount = null,
        private ?string $stringValue = null,
        private array $arrayValues = [],
        private int $probability = 100, // 0-100
    ) {
        if (! in_array($this->field, [
            self::FIELD_FICO,
            self::FIELD_AGE,
            self::FIELD_ANNUAL_INCOME,
            self::FIELD_STATE,
        ])) {
            throw new InvalidArgumentException('Unsupported field');
        }
        if (! in_array($this->operator, [
            self::OPERATOR_GREATER_THAN,
            self::OPERATOR_LESS_THAN,
            self::OPERATOR_GREATER_THAN_OR_EQUAL,
            self::OPERATOR_LESS_THAN_OR_EQUAL,
            self::OPERATOR_NOT_EQUAL,
            self::IN_ARRAY,
        ])) {
            throw new InvalidArgumentException('Unsupported operator');
        }
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function getStringValue(): ?string
    {
        return $this->stringValue;
    }

    /**
     * @return string[]
     */
    public function getArrayValues(): array
    {
        return $this->arrayValues;
    }

    public function getProbability(): int
    {
        return $this->probability;
    }
}