<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Storage;

use App\Shop\Cart\Domain\Event\CartRemoved;
use App\Shop\Cart\Domain\Model\Cart;
use App\Shop\Cart\Domain\Model\CartsInterface;
use App\Shop\Common\Event\PersistableDoctrineEventTrait;
use App\Shop\Common\Exception\ConflictException;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Driver\PDOException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineCarts extends ServiceEntityRepository implements CartsInterface
{
    use PersistableDoctrineEventTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    /**
     * @param Cart $cart
     *
     * @throws StorageException
     * @throws ConflictException
     */
    public function save(Cart $cart): void
    {
        $em = $this->getEntityManager();

        try {
            $em->persist($cart);
            $this->persistModelEvents($cart);
            $em->flush();
        } catch (ORMException $e) {
            throw new StorageException();
        } catch (UniqueConstraintViolationException $e) {
            throw new ConflictException();
        }
    }

    /**
     * @param string $uuid
     *
     * @throws StorageException
     */
    public function remove(string $uuid): void
    {
        $em = $this->getEntityManager();

        try {
            $toRm = $em->getReference(Cart::class, $uuid);
            $em->remove($toRm);
            $this->persistEvent(new CartRemoved($uuid));
            $em->flush();
        } catch (ORMException $e) {
            throw new StorageException();
        }
    }

    /**
     * @return Cart[]
     */
    public function getAll(): array
    {
        return $this->findAll();
    }

    /**
     * @param string $uud
     *
     * @return Cart
     *
     * @throws EntityNotFoundException
     */
    public function getByUuid(string $uud): Cart
    {
        /** @var Cart $cart */
        $cart = $this->find($uud);

        if ($cart === null) {
            throw new EntityNotFoundException();
        }

        return $cart;
    }
}
