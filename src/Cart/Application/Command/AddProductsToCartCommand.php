<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Command;

class AddProductsToCartCommand
{
    private string $cartUuid;
    /** @var string[] */
    private array $productUuidsToAdd;

    public function __construct(string $cartUuid, string... $productUuidToAdd)
    {
        $this->cartUuid = $cartUuid;
        $this->productUuidsToAdd = $productUuidToAdd;
    }

    public function getCartUuid(): string
    {
        return $this->cartUuid;
    }

    /**
     * @return string[]
     */
    public function getProductUuidsToAdd(): array
    {
        return $this->productUuidsToAdd;
    }
}
