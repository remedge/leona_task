<?php

declare(strict_types=1);

namespace App\Product\Domain;

use Ramsey\Uuid\UuidInterface;

interface PolicyRepository
{
    public function save(Policy $policy): void;

    /**
     * @return Policy[]
     */
    public function findAllByProductId(UuidInterface $productId): array;
}