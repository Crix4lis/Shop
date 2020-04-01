<?php

declare(strict_types=1);

namespace App\Shop\Cart\Domain\Model;

use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;

interface CartsInterface
{
    /**
     * @param Cart $cart
     *
     * @throws StorageException
     */
    public function save(Cart $cart): void;

    /**
     * @param string $uud
     *
     * @return Cart
     *
     * @throws EntityNotFoundException
     */
    public function getByUuid(string $uud): Cart;
}
