<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Domain\Event;

use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Price\Price;
use App\Shop\Common\Serializer\JsonSerializer;

class NewProductCreated extends BaseDomainEvent
{
    public function __construct(string $uuid, string $name, Price $price)
    {
        parent::__construct(JsonSerializer::serialize(
            [
                'productUuid' => $uuid,
                'productName' => $name,
                'price' => [
                    'value' => $price->getValue(),
                    'currency' => $price->getCurrency()
                ]
            ]
        ));
    }
}
