<?php

declare(strict_types=1);

namespace App\User\Application\Exception;

use Exception;
use Ramsey\Uuid\UuidInterface;

class UserNotFoundException extends Exception
{
    public function __construct(UuidInterface $id)
    {
        parent::__construct(sprintf('User with id "%s" not found', $id));
    }
}