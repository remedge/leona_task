<?php

declare(strict_types=1);

namespace App\Product\Domain;

interface IssuedProductRepository
{
    public function save(IssuedProduct $issuedProduct): void;
}