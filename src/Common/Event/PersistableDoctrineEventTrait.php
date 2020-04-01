<?php

declare(strict_types=1);

namespace App\Shop\Common\Event;

use App\Shop\Common\Exception\StorageException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Trait to be used within classes which extend:
 * @see ServiceEntityRepository
 */
trait PersistableDoctrineEventTrait
{
    /**
     * @param EventThrowableModel $model
     *
     * @throws StorageException
     */
    protected function persistModelEvents(EventThrowableModel $model): void
    {
        /** @var DoctrineEventStore $eventStore */
        $eventStore = $this->getEntityManager()->getRepository(BaseDomainEvent::class);
        $events = $model->getEvents();

        foreach ($events as $e) {
            $eventStore->append($e->getModel());
        }

        $model->clearEvents();
    }

    /**
     * @param DomainEvent $event
     *
     * @throws StorageException
     */
    protected function persistEvent(DomainEvent $event): void
    {
        /** @var DoctrineEventStore $eventStore */
        $eventStore = $this->getEntityManager()->getRepository(BaseDomainEvent::class);

        $eventStore->append($event->getModel());
    }
}
