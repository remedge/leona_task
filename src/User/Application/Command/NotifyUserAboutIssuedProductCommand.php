<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use Ramsey\Uuid\UuidInterface;

readonly class NotifyUserAboutIssuedProductCommand
{
    public function __construct(
        public UuidInterface $userId,
        public UuidInterface $productId,
    ) {
    }
}