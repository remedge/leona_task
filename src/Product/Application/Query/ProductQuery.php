<?php

declare(strict_types=1);

namespace App\Product\Application\Query;

use App\Finance\Application\Query\IncomeQuery;
use App\Product\Application\DTO\ProductDTO;
use App\Product\Application\Exception\ProductNotFoundException;
use App\Product\Domain\InterestRateCalculator;
use App\Product\Domain\PolicyRepository;
use App\Product\Domain\ProductPossibilityChecker;
use App\Product\Domain\ProductRepository;
use App\Shared\Application\Clock;
use App\User\Application\Query\UserQuery;
use Ramsey\Uuid\UuidInterface;

class ProductQuery
{
    public function __construct(
        public ProductRepository $productRepository,
        public PolicyRepository $policyRepository,
        public ProductPossibilityChecker $possibilityChecker,
        public UserQuery $userQuery,
        public IncomeQuery $incomeQuery,
        public Clock $clock,
        public InterestRateCalculator $interestRateCalculator,
    ) {
    }

    public function getById(UuidInterface $id): ProductDTO
    {
        $product = $this->productRepository->findById($id);

        if ($product === null) {
            throw new ProductNotFoundException($id);
        }

        return new ProductDTO(
            id: $product->getId(),
            name: $product->getName(),
            term: $product->getTerm(),
            interestRate: $product->getInterestRate(),
        );
    }

    public function checkPossibilityForUser(UuidInterface $productId, UuidInterface $userId): bool
    {
        $policies = $this->policyRepository->findAllByProductId($productId);

        $user = $this->userQuery->getById($userId);

        return $this->possibilityChecker->check(
            policies: $policies,
            fico: $user->fico,
            income: $this->incomeQuery->getMonthlyIncomeByUser($userId),
            age: $user->birthdate->diff($this->clock->now())->y,
            state: $user->state,
        );
    }

    public function calculateInterestRate(float $interestRate, string $state): float
    {
        return $this->interestRateCalculator->calculate($interestRate, $state);
    }
}