<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\FileStorage;

use App\Product\Domain\Policy;
use App\Product\Domain\PolicyRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileStoragePolicyRepository implements PolicyRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private string $dbPath,
    ) {
    }

    public function save(Policy $policy): void
    {
        $this->filesystem->dumpFile(
            $this->dbPath . '/policies/' . $policy->getId() . '.json',
            json_encode([
                'id' => $policy->getId(),
                'productId' => $policy->getProductId(),
                'field' => $policy->getField(),
                'operator' => $policy->getOperator(),
                'amount' => $policy->getAmount(),
                'stringValue' => $policy->getStringValue(),
                'arrayValues' => $policy->getArrayValues(),
                'probability' => $policy->getProbability(),
            ])
        );
    }

    public function findAllByProductId(UuidInterface $productId): array
    {
        $directory = $this->dbPath . '/policies/';
        $files = scandir($directory);

        $policies = [];
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }

            /** @var array{id: string, productId: string, field: string, operator: string, amount: int, stringValue: string, arrayValues: array<string>, probability: int} $policy */
            $policy = json_decode(file_get_contents($directory . $file), true);

            if ($policy['productId'] === $productId->toString()) {
                $policies[] = new Policy(
                    id: Uuid::fromString($policy['id']),
                    productId: Uuid::fromString($policy['productId']),
                    field: $policy['field'],
                    operator: $policy['operator'],
                    amount: $policy['amount'],
                    stringValue: $policy['stringValue'],
                    arrayValues: $policy['arrayValues'],
                    probability: $policy['probability'],
                );
            }
        }

        return $policies;
    }
}