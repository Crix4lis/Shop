<?php

declare(strict_types=1);

namespace App\Shop\Cart\Domain\Event;

use App\Shop\Cart\Domain\Model\Product;
use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Serializer\JsonSerializer;

class ProductRemovedFromCart extends BaseDomainEvent
{
    public function __construct(string $toCartUuid, Product $product)
    {
        parent::__construct($this->createBody($toCartUuid, $product));
    }

    protected function createBody(string $cartUuid, Product $product): string
    {
        return JsonSerializer::serialize(['cartUuid' => $cartUuid, 'product' => $product->getProductUuid()]);
    }
}
