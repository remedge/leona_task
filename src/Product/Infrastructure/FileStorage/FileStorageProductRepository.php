<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\FileStorage;

use App\Product\Domain\Product;
use App\Product\Domain\ProductRepository;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileStorageProductRepository implements ProductRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private string $dbPath,
    ) {
    }

    public function save(Product $product): void
    {
        $this->filesystem->dumpFile(
            $this->dbPath . '/products/' . $product->getId() . '.json',
            json_encode([
                'id' => $product->getId(),
                'name' => $product->getName(),
                'term' => $product->getTerm(),
                'interestRate' => $product->getInterestRate(),
            ])
        );
    }

    public function findById(UuidInterface $id): ?Product
    {
        $productFile = $this->dbPath . '/products/' . $id . '.json';

        if (! $this->filesystem->exists($productFile)) {
            return null;
        }

        /** @var array{id: string, name: string, term: int, interestRate: float, amount: int} $productArray */
        $productArray = json_decode(file_get_contents($productFile), true);

        return new Product(
            id: Uuid::fromString($productArray['id']),
            name:  $productArray['name'],
            term: $productArray['term'],
            interestRate: $productArray['interestRate'],
            amount: $productArray['amount'],
        );
    }
}