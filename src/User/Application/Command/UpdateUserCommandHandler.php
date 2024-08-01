<?php

declare(strict_types=1);

namespace App\User\Application\Command;

use App\Shared\Application\Clock;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\FicoCalculator;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class UpdateUserCommandHandler
{
    public function __construct(
        private UserRepository $repository,
        private FicoCalculator $ficoCalculator,
        private Clock $clock,
    ) {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->repository->findById($command->id);
        if ($user === null) {
            throw new UserNotFoundException($command->id);
        }

        if ($command->firstName !== null) {
            $user->setFirstName($command->firstName);
        }
        if ($command->lastName !== null) {
            $user->setLastName($command->lastName);
        }
        if ($command->birthdate !== null) {
            $user->setBirthdate($command->birthdate);
        }
        if ($command->email !== null) {
            $user->setEmail($command->email);
        }
        if ($command->phoneNumber !== null) {
            $user->setPhoneNumber($command->phoneNumber);
        }
        if ($command->city !== null) {
            $user->setCity($command->city);
        }
        if ($command->state !== null) {
            $user->setState($command->state);
        }
        if ($command->zip !== null) {
            $user->setZip($command->zip);
        }
        if ($command->ssn !== null) {
            $user->setSsn($command->ssn);
        }

        if ($command->birthdate !== null || $command->city !== null) {
            $user->setFico($this->ficoCalculator->calculate(
                userAge: ($command->birthdate !== null)
                    ? $this->clock->now()->diff($command->birthdate)->y
                    : $this->clock->now()->diff($user->getBirthdate())->y,
                city: $command->city ?? $user->getCity(),
            ));
        }

        $this->repository->save($user);
    }
}