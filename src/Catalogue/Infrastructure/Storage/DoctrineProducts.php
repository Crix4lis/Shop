<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Infrastructure\Storage;

use App\Shop\Catalogue\Domain\Event\ProductRemoved;
use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Catalogue\Domain\Model\ProductsInterface;
use App\Shop\Common\Event\PersistableDoctrineEventTrait;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineProducts extends ServiceEntityRepository implements ProductsInterface
{
    use PersistableDoctrineEventTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param Product $product
     *
     * @throws StorageException
     */
    public function save(Product $product): void
    {
        $em = $this->getEntityManager();
        try {
            $em->persist($product);
            $this->persistModelEvents($product);
            $em->flush();
        } catch (ORMException $e) {
            throw new StorageException();
        }
    }

    /**
     * @param string $uud
     *
     * @return Product
     *
     * @throws EntityNotFoundException
     */
    public function getByUuid(string $uud): Product
    {
        /** @var Product $product */
        $product = $this->find($uud);

        if ($product === null) {
            throw new EntityNotFoundException();
        }

        return $product;
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
            $toRm = $em->getReference(Product::class, $uuid);
            $em->remove($toRm);
            $this->persistEvent(new ProductRemoved($uuid));
            $em->flush();
        } catch (ORMException $e) {
            throw new StorageException();
        }
    }
}
