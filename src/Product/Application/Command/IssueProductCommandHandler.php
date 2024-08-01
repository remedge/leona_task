<?php

declare(strict_types=1);

namespace App\Product\Application\Command;

use App\Product\Application\Event\ProductIssuedEvent;
use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Domain\InterestRateCalculator;
use App\Product\Domain\IssuedProduct;
use App\Product\Domain\IssuedProductRepository;
use App\Product\Domain\ProductRepository;
use App\Shared\Application\Clock;
use App\Shared\Application\UuidProvider;
use App\User\Application\Query\UserQuery;
use DateInterval;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class IssueProductCommandHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private Clock $clock,
        private UuidProvider $uuidProvider,
        private InterestRateCalculator $interestRateCalculator,
        private UserQuery $userQuery,
        private IssuedProductRepository $issuedProductRepository,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function __invoke(IssueProductCommand $command): void
    {
        $product = $this->productRepository->findById($command->productId);
        if ($product === null) {
            throw new ProductNotFoundException($command->productId);
        }

        $user = $this->userQuery->getById($command->userId);

        $this->issuedProductRepository->save(new IssuedProduct(
            id: $this->uuidProvider->provide(),
            productId: $command->productId,
            issuedTo: $command->userId,
            issuedAt: $this->clock->now(),
            issuedUntil: $this->clock->now()->add(new DateInterval("P{$product->getTerm()}M")),
            interestRate: $this->interestRateCalculator->calculate(
                $product->getInterestRate(),
                $user->state
            ),
            amount: $product->getAmount(),
        ));

        $this->eventDispatcher->dispatch(new ProductIssuedEvent($command->productId, $command->userId));
    }
}