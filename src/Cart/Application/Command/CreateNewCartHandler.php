<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Command;

use App\Shop\Cart\Domain\Model\Cart;
use App\Shop\Cart\Domain\Model\Product;
use App\Shop\Cart\Infrastructure\Storage\DoctrineCarts;
use App\Shop\Common\Exception\StorageException;

class CreateNewCartHandler
{
    private DoctrineCarts $carts;

    public function __construct(DoctrineCarts $carts)
    {
        $this->carts = $carts;
    }

    /**
     * @param CreateNewCartCommand $command
     *
     * @throws StorageException
     * @throws \InvalidArgumentException
     */
    public function handle(CreateNewCartCommand $command): void
    {
        $newCart = Cart::createNewCart($command->getCartUuid(), new Product($command->getProductsUuid()));

        $this->carts->save($newCart);
    }
}
