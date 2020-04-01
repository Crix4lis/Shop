<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Domain\Event;

use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Price\Price;
use App\Shop\Common\Serializer\JsonSerializer;

class ProductPriceChanged extends BaseDomainEvent
{
    public function __construct(string $uuid, Price $price)
    {
        parent::__construct(JsonSerializer::serialize([
            'productUuid' => $uuid,
            'newProductPriceValue' => $price->getValue()
        ]));
    }
}
