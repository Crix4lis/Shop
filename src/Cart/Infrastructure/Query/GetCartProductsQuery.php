<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Query;

use App\Shop\Cart\Application\Query\GetCartProducts;
use App\Shop\Cart\Domain\Model\Cart;
use App\Shop\Cart\Domain\Model\Product as ProductReference;
use App\Shop\Cart\Infrastructure\Parser\ProductDataParserInterface;
use App\Shop\Cart\Infrastructure\Storage\DoctrineCarts;
use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Common\Exception\EntityNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GetCartProductsQuery extends ServiceEntityRepository implements GetCartProducts
{
    private ProductDataParserInterface $productsParser;
    /** @var int */
    private const MAX_RESULTS = 3;

    public function __construct(ManagerRegistry $registry, ProductDataParserInterface $productDataParser)
    {
        parent::__construct($registry, Product::class);
        $this->productsParser = $productDataParser;
    }

    /**
     * @param string $uuid
     * @param int    $page
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     * @throws EntityNotFoundException
     */
    public function execute(string $uuid, int $page): array
    {
        /** @var DoctrineCarts $carts */
        $carts = $this->getEntityManager()->getRepository(Cart::class);
        $cart = $carts->getByUuid($uuid);
        $productsUuid = $cart->getProducts();

        $uniqueProductsUuidWithQuantity = $this->getUniqueProductsUuid(...$productsUuid);
        [$productsInCurrentPage, $restUniqueProducts] = $this->getForCurrentPage($uniqueProductsUuidWithQuantity, $page);

        $productsToReturn = $this->findBy(['uuid' => array_keys($productsInCurrentPage)]);
        $parsedProductsToReturn = $this->productsParser->parse($productsToReturn, $productsInCurrentPage);

        $parsedProductsToReturn['uuid'] = $uuid;
        $parsedProductsToReturn['page'] = $page;
        $parsedProductsToReturn['total_price_currency'] = 'PLN'; //only PLN

        //if all products are on current page - return result
        if (empty($restUniqueProducts)) {
            $parsedProductsToReturn['total_price_value'] = $parsedProductsToReturn['currentCartValue'] === null ?
                0 : (string) $parsedProductsToReturn['currentCartValue']->getValue();
            unset($parsedProductsToReturn['currentCartValue']);

            return $parsedProductsToReturn;
        }

        //count price for current and the rest
        $rest = $this->findBy(['uuid' => array_keys($restUniqueProducts)]);
        $parsedRest = $this->productsParser->parse($rest, $restUniqueProducts);

        $parsedProductsToReturn['total_price_value'] = $parsedProductsToReturn['currentCartValue'] === null ?
            (string) $parsedRest['currentCartValue']->getValue() : (string) $parsedRest['currentCartValue']
                ->add($parsedProductsToReturn['currentCartValue'])
                ->getValue();
        unset($parsedProductsToReturn['currentCartValue']);

        return $parsedProductsToReturn;
    }

    private function getForCurrentPage(array $uniqueProductsUuidWithQuantity, int $page): array
    {
        $currentPageProducts = array_slice(
            $uniqueProductsUuidWithQuantity,
            $this->getOffset($page),
            self::MAX_RESULTS,
            true
        );

        $rest = array_diff_assoc($uniqueProductsUuidWithQuantity, $currentPageProducts);

        return [$currentPageProducts, $rest];
    }

    private function getOffset(int $page): int
    {
        return ($page - 1) * self::MAX_RESULTS;
    }

    private function getUniqueProductsUuid(ProductReference... $productsUud): array
    {
        $uniqueProductUuids = [];

        foreach ($productsUud as $puuid) {
            if (array_key_exists($puuid->getProductUuid(), $uniqueProductUuids)) {
                $uniqueProductUuids[$puuid->getProductUuid()] += 1;
                continue;
            }

            $uniqueProductUuids[$puuid->getProductUuid()] = 1;
        }

        return $uniqueProductUuids;
    }
}
