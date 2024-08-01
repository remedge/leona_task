<?php

declare(strict_types=1);

namespace App\User\Application\Query;

use App\User\Application\DTO\UserDTO;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\UserRepository;
use Ramsey\Uuid\UuidInterface;

class UserQuery
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
    }

    public function getById(UuidInterface $id): UserDTO
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new UserNotFoundException($id);
        }

        return new UserDTO(
            id: $user->getId(),
            firstName: $user->getFirstName(),
            lastName: $user->getLastName(),
            birthdate: $user->getBirthdate(),
            email: $user->getEmail(),
            phoneNumber: $user->getPhoneNumber(),
            city: $user->getCity(),
            state: $user->getState(),
            zip: $user->getZip(),
            ssn: $user->getSsn(),
            fico: $user->getFico(),
        );
    }
}