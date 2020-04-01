<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Command;

use App\Shop\Cart\Domain\Model\Product;
use App\Shop\Cart\Infrastructure\Storage\DoctrineCarts;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;

class RemoveProductFromCartHandler
{
    private DoctrineCarts $carts;

    public function __construct(DoctrineCarts $carts)
    {
        $this->carts = $carts;
    }

    /**
     * @param RemoveProductFromCartCommand $command
     *
     * @throws StorageException
     * @throws EntityNotFoundException
     */
    public function handle(RemoveProductFromCartCommand $command): void
    {
        $cart = $this->carts->getByUuid($command->getCartUuid());
        $cart->removeProduct(new Product($command->getProductUuidToRemove()));
        $this->carts->save($cart);

        //remove cart if no products
        if (count($cart->getProducts()) === 0) {
            $this->carts->remove($command->getCartUuid());
        }
    }
}
