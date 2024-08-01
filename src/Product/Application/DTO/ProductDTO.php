<?php

declare(strict_types=1);

namespace App\Product\Application\DTO;

use Ramsey\Uuid\UuidInterface;

readonly class ProductDTO
{
    public function __construct(
        public UuidInterface $id,
        public string $name,
        public int $term,
        public float $interestRate,
    ) {
    }
}