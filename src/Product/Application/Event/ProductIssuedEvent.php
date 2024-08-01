<?php

declare(strict_types=1);

namespace App\Product\Application\Event;

use Ramsey\Uuid\UuidInterface;

class ProductIssuedEvent
{
    public function __construct(
        public UuidInterface $productId,
        public UuidInterface $userId,
    ) {
    }
}