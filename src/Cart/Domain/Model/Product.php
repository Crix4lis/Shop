<?php

declare(strict_types=1);

namespace App\Shop\Cart\Domain\Model;

use Webmozart\Assert\Assert;

class Product
{
    private int $id;
    private string $productUuid;

    public function __construct(string $productUuid)
    {
        Assert::uuid($productUuid);

        $this->productUuid = $productUuid;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }
}
