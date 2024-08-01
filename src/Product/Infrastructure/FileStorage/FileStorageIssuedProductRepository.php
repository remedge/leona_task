<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\FileStorage;

use App\Product\Domain\IssuedProduct;
use App\Product\Domain\IssuedProductRepository;
use Symfony\Component\Filesystem\Filesystem;

readonly class FileStorageIssuedProductRepository implements IssuedProductRepository
{
    public function __construct(
        private Filesystem $filesystem,
        private string $dbPath,
    ) {
    }

    public function save(IssuedProduct $issuedProduct): void
    {
        $this->filesystem->dumpFile(
            $this->dbPath . '/issued_products/' . $issuedProduct->getId() . '.json',
            json_encode([
                'id' => $issuedProduct->getId(),
                'productId' => $issuedProduct->getProductId(),
                'issuedTo' => $issuedProduct->getIssuedTo(),
                'issuedAt' => $issuedProduct->getIssuedAt()->getTimestamp(),
                'issuedUntil' => $issuedProduct->getIssuedUntil()->getTimestamp(),
                'interestRate' => $issuedProduct->getInterestRate(),
                'amount' => $issuedProduct->getAmount(),
            ])
        );
    }
}