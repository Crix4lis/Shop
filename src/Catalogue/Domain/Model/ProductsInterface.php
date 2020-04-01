<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Domain\Model;

use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;

interface ProductsInterface
{
    /**
     * @param Product $product
     *
     * @throws StorageException
     */
    public function save(Product $product): void;

    /**
     * @param string $uud
     *
     * @return Product
     *
     * @throws EntityNotFoundException
     */
    public function getByUuid(string $uud): Product;
}
