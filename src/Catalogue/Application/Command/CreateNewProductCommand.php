<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Command;

class CreateNewProductCommand
{
    private string $productUuid;
    private string $name;
    private float $price;
    private string $currency;

    public function __construct(string $uuid, string $name, float $price, string $currency)
    {
        $this->productUuid = $uuid;
        $this->name = $name;
        $this->price = $price;
        $this->currency = $currency;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
