<?php

declare(strict_types=1);

namespace App\Shop\Common\Event;

use App\Shop\Common\Exception\StorageException;

interface EventStoreInterface
{
    /**
     * @param DomainEvent $event
     *
     * @throws StorageException
     */
    public function append(DomainEvent $event): void;
}
