<?php

declare(strict_types=1);

namespace App\Shop\Common\Event;

use App\Shop\Common\Exception\StorageException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineEventStore extends ServiceEntityRepository implements EventStoreInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseDomainEvent::class);
    }

    /**
     * @param DomainEvent $event
     *
     * @throws StorageException
     */
    public function append(DomainEvent $event): void
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($event);
        } catch (ORMException $e) {
            throw new StorageException();
        }
    }
}
