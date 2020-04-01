<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Command;

use App\Shop\Catalogue\Infrastructure\Storage\DoctrineProducts;
use App\Shop\Common\Exception\StorageException;

class RemoveProductHandler
{
    private DoctrineProducts $products;

    public function __construct(DoctrineProducts $products)
    {
        $this->products = $products;
    }

    /**
     * @param RemoveProductCommand $command
     *
     * @throws StorageException
     */
    public function handle(RemoveProductCommand $command): void
    {
        $this->products->remove($command->getUuidToRemove());
    }
}
