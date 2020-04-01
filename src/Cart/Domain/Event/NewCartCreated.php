<?php

declare(strict_types=1);

namespace App\Shop\Cart\Domain\Event;

use App\Shop\Cart\Domain\Model\Product;
use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Serializer\JsonSerializer;

class NewCartCreated extends BaseDomainEvent
{
    public function __construct(string $fromCartUuid, Product $products)
    {
        parent::__construct($this->createBody($fromCartUuid, $products));
    }

    protected function createBody(string $cartUuid, Product $product): string
    {
        $data = [
            'uuid' => $cartUuid,
            'productUuid' => $product->getProductUuid()
        ];

        return JsonSerializer::serialize($data);
    }
}
