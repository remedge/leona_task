<?php

declare(strict_types=1);

namespace App\User\Presentation\Cli;

use App\User\Application\Command\UpdateUserCommand;
use App\User\Application\Query\UserQuery;
use DateTimeImmutable;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'user:update', description: 'Update user')]
class UpdateUserCliCommand extends Command
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private UserQuery $userQuery,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'User id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            /** @var string $id */
            $id = $input->getArgument('id');
            $userUuid = Uuid::fromString($id);
            $this->userQuery->getById($userUuid);
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');
        /** @var string|null $firstName */
        $firstName = $helper->ask($input, $output, new Question('Please enter the first name (or press enter to skip): '));
        /** @var string|null $lastName */
        $lastName = $helper->ask($input, $output, new Question('Please enter the last name (or press enter to skip): '));

        do {
            /** @var string|null $birthdateString */
            $birthdateString = $helper->ask($input, $output, new Question('Please enter the birthdate (dd.mm.yyyy) or press enter to skip: '));
            if ($birthdateString === null) {
                $birthdate = null;
            } else {
                $birthdate = DateTimeImmutable::createFromFormat('d.m.Y', $birthdateString);
                if ($birthdate === false) {
                    $birthdate = null;
                } else {
                    $birthdate = $birthdate->setTime(0, 0);
                }
            }
        } while ($birthdateString !== null && $birthdate === null);

        do {
            /** @var string|null $email */
            $email = $helper->ask($input, $output, new Question('Please enter valid email (or press enter to skip): '));
            if ($email === null) {
                break;
            }
        } while (! filter_var($email, FILTER_VALIDATE_EMAIL));

        do {
            /** @var string|null $phoneNumber */
            $phoneNumber = $helper->ask($input, $output, new Question('Please enter valid phone number (or press enter to skip): '));
            if ($phoneNumber === null) {
                break;
            }
        } while (! preg_match('/^\d+$/', $phoneNumber));

        /** @var string|null $city */
        $city = $helper->ask($input, $output, new Question('Please enter the city (or press enter to skip): '));

        do {
            /** @var string|null $state */
            $state = $helper->ask($input, $output, new Question('Please enter the state (2 uppercase letters) or press enter to skip: '));
            if ($state === null) {
                break;
            }
        } while (! preg_match('/^[A-Z]{2}$/', $state));

        do {
            /** @var string|null $zip */
            $zip = $helper->ask($input, $output, new Question('Please enter the zip (5 digits) or press enter to skip: '));
            if ($zip === null) {
                break;
            }
        } while (! preg_match('/^\d{5}$/', $zip));

        do {
            /** @var string|null $ssn */
            $ssn = $helper->ask($input, $output, new Question('Please enter the ssn (9 digits) or press enter to skip: '));
            if ($ssn === null) {
                break;
            }
        } while (! preg_match('/^\d{9}$/', $ssn));

        if ($firstName !== null ||
            $lastName !== null ||
            $birthdate !== null ||
            $email !== null ||
            $phoneNumber !== null ||
            $city !== null ||
            $state !== null ||
            $zip !== null ||
            $ssn !== null) {
            $this->messageBus->dispatch(
                new UpdateUserCommand(
                    id: $userUuid,
                    firstName: $firstName,
                    lastName: $lastName,
                    birthdate: $birthdate,
                    email: $email,
                    phoneNumber: $phoneNumber,
                    city: $city,
                    state: $state,
                    zip: $zip,
                    ssn: $ssn
                )
            );
            $output->writeln('User successfully updated');
        } else {
            $output->writeln('Nothing to update');
        }

        return Command::SUCCESS;
    }
}