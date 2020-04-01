<?php

declare(strict_types=1);

namespace App\Shop\Common\Event;

interface EventThrowableModel
{
    /** @return DomainEvent[] */
    public function getEvents(): array;
    public function clearEvents(): void;
}
