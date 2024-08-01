<?php

declare(strict_types=1);

namespace App\Product\Application\Command;

use Ramsey\Uuid\UuidInterface;

readonly class IssueProductCommand
{
    public function __construct(
        public UuidInterface $productId,
        public UuidInterface $userId,
    ) {
    }
}