<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Command;

use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Catalogue\Domain\Model\ProductsInterface;
use App\Shop\Common\Exception\StorageException;
use App\Shop\Common\Price\Price;

class CreateNewProductHandler
{
    private ProductsInterface $products;

    public function __construct(ProductsInterface $products)
    {
        $this->products = $products;
    }

    /**
     * @param CreateNewProductCommand $command
     *
     * @throws StorageException
     */
    public function handle(CreateNewProductCommand $command): void
    {
        $newProduct = Product::createNew(
            $command->getProductUuid(),
            $command->getName(),
            Price::createPLN($command->getPrice())
        );

        $this->products->save($newProduct);
    }
}
