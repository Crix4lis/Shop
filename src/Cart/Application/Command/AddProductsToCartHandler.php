<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Command;

use App\Shop\Cart\Domain\Model\Product;
use App\Shop\Cart\Infrastructure\Storage\DoctrineCarts;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;

class AddProductsToCartHandler
{
    private DoctrineCarts $carts;

    public function __construct(DoctrineCarts $carts)
    {
        $this->carts = $carts;
    }

    /**
     * @param AddProductsToCartCommand $command
     *
     * @throws EntityNotFoundException
     * @throws StorageException
     */
    public function handle(AddProductsToCartCommand $command): void
    {
        $cart = $this->carts->getByUuid($command->getCartUuid());

        $uuids = $command->getProductUuidsToAdd();
        foreach ($uuids as $productUuid) {
            $cart->addProduct(new Product($productUuid));
        }

        $this->carts->save($cart);
    }
}
