<?php

declare(strict_types=1);

namespace App\Product\Presentation\Cli;

use App\Product\Application\Command\IssueProductCommand;
use App\Product\Application\Query\ProductQuery;
use App\User\Application\Query\UserQuery;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(name: 'product:issue', description: 'Issue product for the user.')]
class IssueProductCliCommand extends Command
{
    public function __construct(
        private readonly ProductQuery $productQuery,
        private readonly UserQuery $userQuery,
        private readonly MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Issue product for the user.')
            ->setHelp('This command allows you to issue product for the user.')
            ->addArgument('productId', InputArgument::REQUIRED, 'Product id')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $productUuid = Uuid::fromString($input->getArgument('productId'));
            $this->productQuery->getById($productUuid);

            $userUuid = Uuid::fromString($input->getArgument('userId'));
            $this->userQuery->getById($userUuid);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        $isPossible = $this->productQuery->checkPossibilityForUser($productUuid, $userUuid);

        if ($isPossible === false) {
            $output->writeln('The product is not possible for the user.');
            return Command::FAILURE;
        }

        $this->messageBus->dispatch(new IssueProductCommand(
            productId: $productUuid,
            userId: $userUuid
        ));

        $output->writeln('Product issued successfully.');

        return Command::SUCCESS;
    }
}