<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Infrastructure\Query;

use App\Shop\Catalogue\Application\Query\GetManyProducts;
use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Catalogue\Infrastructure\Parser\ProductModelParser;
use App\Shop\Common\Exception\StorageException;
use App\Shop\Common\Parser\ParserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Webmozart\Assert\Assert;

class GetManyProductsQuery extends ServiceEntityRepository implements GetManyProducts
{
    private ParserInterface $parser;

    public function __construct(ManagerRegistry $registry, ProductModelParser $parser)
    {
        parent::__construct($registry, Product::class);
        $this->parser = $parser;
    }

    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     * @throws StorageException
     */
    public function execute(int $page, int $perPage = 3): array
    {
        Assert::greaterThan($page, 0);
        Assert::greaterThan($perPage, 0);

        $first = ($page - 1) * $perPage;
        $em = $this->getEntityManager();

        $query = $em->createQuery("SELECT product FROM App\Shop\Catalogue\Domain\Model\Product product");
        $query->setFirstResult($first)->setMaxResults($perPage);

        try {
            /** @var Product[] $result */
            $result = $query->execute();
        } catch (\Exception $e) {
            throw new StorageException();
        }

        $data = [];
        foreach ($result as $row) {
            $data[] = $this->parser->parse($row);
        }

        return $data;
    }
}
