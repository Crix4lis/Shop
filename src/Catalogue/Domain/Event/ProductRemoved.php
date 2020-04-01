<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Domain\Event;

use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Serializer\JsonSerializer;

class ProductRemoved extends BaseDomainEvent
{
    public function __construct(string $uuid)
    {
        parent::__construct(JsonSerializer::serialize([
            'productUuid' => $uuid
        ]));
    }
}
