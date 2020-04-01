<?php

declare(strict_types=1);

namespace App\Shop\Common\Event;

class BaseDomainEvent implements DomainEvent
{
    private int $eventId;
    private string $eventBody;
    private \DateTimeImmutable $occurredOn;
    private string $typeName;
    private string $inheritance;

    public function __construct(string $eventBody)
    {
        $this->eventBody = $eventBody;
        $this->occurredOn = new \DateTimeImmutable();
        $this->typeName = static::class;
    }

    public function getEventId(): ?int
    {
        return $this->eventId;
    }

    public function getEventBody(): string
    {
        return $this->eventBody;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }

    public function getTypeName(): string
    {
        return $this->typeName;
    }

    public function getModel(): BaseDomainEvent
    {
        $baseEvent = new self('');
        $baseEvent->eventBody = $this->eventBody;
        $baseEvent->occurredOn = $this->occurredOn;
        $baseEvent->typeName = $this->typeName;

        return $baseEvent;
    }
}
