<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Command;

class RemoveProductFromCartCommand
{
    private string $cartUuid;
    private string $productUuidToRemove;

    public function __construct(string $cartUuid, string $productUuidToRemove)
    {
        $this->cartUuid = $cartUuid;
        $this->productUuidToRemove = $productUuidToRemove;
    }

    public function getCartUuid(): string
    {
        return $this->cartUuid;
    }

    public function getProductUuidToRemove(): string
    {
        return $this->productUuidToRemove;
    }
}
