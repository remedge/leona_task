<?php

declare(strict_types=1);

namespace App\Product\Application\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class ProductNotFoundException extends Exception
{
    public function __construct(UuidInterface $id)
    {
        parent::__construct(sprintf('Product with id "%s" not found', $id->toString()));
    }
}