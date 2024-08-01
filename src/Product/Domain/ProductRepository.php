<?php

declare(strict_types=1);

namespace App\Product\Domain;

use Ramsey\Uuid\UuidInterface;

interface ProductRepository
{
    public function save(Product $product): void;

    public function findById(UuidInterface $id): ?Product;
}