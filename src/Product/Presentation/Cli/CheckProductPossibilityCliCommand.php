<?php

declare(strict_types=1);

namespace App\Product\Presentation\Cli;

use App\Product\Application\Query\ProductQuery;
use App\User\Application\Query\UserQuery;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'product:check', description: 'Check product possibility for the client.')]
class CheckProductPossibilityCliCommand extends Command
{
    public function __construct(
        private readonly ProductQuery $productQuery,
        private readonly UserQuery $userQuery,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Check product possibility for the client.')
            ->setHelp('This command allows you to check product possibility for the client.')
            ->addArgument('productId', InputArgument::REQUIRED, 'Product id')
            ->addArgument('userId', InputArgument::REQUIRED, 'User id')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $productUuid = Uuid::fromString($input->getArgument('productId'));
            $product = $this->productQuery->getById($productUuid);

            $userUuid = Uuid::fromString($input->getArgument('userId'));
            $user = $this->userQuery->getById($userUuid);
        } catch (Exception $e) {
            $output->writeln($e->getMessage());
            return Command::FAILURE;
        }

        $isPossible = $this->productQuery->checkPossibilityForUser($productUuid, $userUuid);

        $output->writeln('The product is ' . ($isPossible ? 'possible' : 'not possible') . ' for the user.');

        if ($isPossible === true) {
            $interestRate = $this->productQuery->calculateInterestRate($product->interestRate, $user->state);
            $output->writeln('Interest rate: ' . $interestRate);
        }

        return Command::SUCCESS;
    }
}