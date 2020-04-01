<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Command;

class UpdateProductDataCommand
{
    private string $productUuid;
    private ?string $newName;
    private ?string $newValue;

    public function __construct(string $productUuid, ?string $newName, ?string $newValue)
    {
        $this->productUuid = $productUuid;
        $this->newName = $newName;
        $this->newValue = $newValue;
    }

    public function getProductUuid(): string
    {
        return $this->productUuid;
    }

    public function getNewName(): ?string
    {
        return $this->newName;
    }

    public function getNewValue(): ?string
    {
        return $this->newValue;
    }
}
