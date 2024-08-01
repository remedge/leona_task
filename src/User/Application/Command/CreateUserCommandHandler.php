<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\Shared\Application\Clock;
use App\Shared\Application\UuidProvider;
use App\User\Domain\FicoCalculator;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class CreateUserCommandHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private UuidProvider $uuidProvider,
        private FicoCalculator $ficoCalculator,
        private Clock $clock,
    ) {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        $user = new User(
            id: $this->uuidProvider->provide(),
            firstName: $command->firstName,
            lastName: $command->lastName,
            birthdate: $command->birthdate,
            email: $command->email,
            phoneNumber: $command->phoneNumber,
            city: $command->city,
            state: $command->state,
            zip: $command->zip,
            ssn: $command->ssn,
            fico: $this->ficoCalculator->calculate(
                userAge: $this->clock->now()->diff($command->birthdate)->y,
                city: $command->city,
            ),
        );

        $this->userRepository->save($user);
    }
}