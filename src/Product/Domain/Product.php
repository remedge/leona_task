<?php

declare(strict_types=1);

namespace App\Product\Domain;

use Ramsey\Uuid\UuidInterface;

class Product
{
    public function __construct(
        private UuidInterface $id,
        private string $name,
        private int $term, // in months
        private float $interestRate, // in percents of 100
        private int $amount,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTerm(): int
    {
        return $this->term;
    }

    public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }
}