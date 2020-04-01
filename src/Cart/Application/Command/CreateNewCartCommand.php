<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Command;

class CreateNewCartCommand
{
    private string $cartUuid;
    private string $productUuid;

    public function __construct(string $cartUuid, $productUuid)
    {
        $this->cartUuid = $cartUuid;
        $this->productUuid = $productUuid;
    }

    public function getCartUuid(): string
    {
        return $this->cartUuid;
    }

    public function getProductsUuid(): string
    {
        return $this->productUuid;
    }
}
