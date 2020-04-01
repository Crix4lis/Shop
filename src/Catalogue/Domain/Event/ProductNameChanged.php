<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Domain\Event;

use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Serializer\JsonSerializer;

class ProductNameChanged extends BaseDomainEvent
{
    public function __construct(string $uuid, string $name)
    {
        parent::__construct(JsonSerializer::serialize([
            'productUuid' => $uuid,
            'newProductName' => $name
        ]));
    }
}
