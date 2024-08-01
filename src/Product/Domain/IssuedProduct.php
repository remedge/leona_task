<?php

declare(strict_types=1);

namespace App\Product\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

class IssuedProduct
{
    public function __construct(
        private UuidInterface $id,
        private UuidInterface $productId,
        private UuidInterface $issuedTo,
        private DateTimeImmutable $issuedAt,
        private DateTimeImmutable $issuedUntil,
        private float $interestRate,
        private int $amount,
    ) {
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getProductId(): UuidInterface
    {
        return $this->productId;
    }

    public function getIssuedTo(): UuidInterface
    {
        return $this->issuedTo;
    }

    public function getIssuedAt(): DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function getIssuedUntil(): DateTimeImmutable
    {
        return $this->issuedUntil;
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