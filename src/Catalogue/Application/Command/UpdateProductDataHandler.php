<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Command;

use App\Shop\Catalogue\Infrastructure\Storage\DoctrineProducts;
use App\Shop\Common\Exception\StorageException;
use App\Shop\Common\Price\Price;

class UpdateProductDataHandler
{
    private DoctrineProducts $products;

    public function __construct(DoctrineProducts $products)
    {
        $this->products = $products;
    }

    /**
     * @param UpdateProductDataCommand $command
     *
     * @throws StorageException
     */
    public function handle(UpdateProductDataCommand $command): void
    {
        $product = $this->products->getByUuid($command->getProductUuid());

        if ($command->getNewName() !== null) {
            $product->changeName($command->getNewName());
        }

        if ($command->getNewValue() !== null) {
            $product->addNewPrice(Price::createPLN((float) $command->getNewValue()));
        }

        $this->products->save($product);
    }
}
