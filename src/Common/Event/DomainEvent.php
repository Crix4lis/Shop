<?php

declare(strict_types=1);

namespace App\Shop\Common\Event;

interface DomainEvent
{
    public function getEventId(): ?int;
    public function getEventBody(): string;
    public function getOccurredOn(): \DateTimeImmutable;
    public function getTypeName(): string;
    public function getModel(): DomainEvent;
}
