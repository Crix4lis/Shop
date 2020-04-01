<?php

declare(strict_types=1);

namespace App\Shop\Cart\Domain\Event;

use App\Shop\Common\Event\BaseDomainEvent;
use App\Shop\Common\Serializer\JsonSerializer;

class CartRemoved extends BaseDomainEvent
{
    public function __construct(string $removedCartUuid)
    {
        parent::__construct(JsonSerializer::serialize(['cartUuid' => $removedCartUuid]));
    }
}
