<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Infrastructure\Query;

use App\Shop\Catalogue\Application\Query\GetProduct;
use App\Shop\Catalogue\Infrastructure\Storage\DoctrineProducts;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Parser\ParserInterface;

class GetProductQuery implements GetProduct
{
    private DoctrineProducts $products;
    private ParserInterface $parser;

    public function __construct(
        DoctrineProducts $products,
        ParserInterface $parser
    )
    {
        $this->products = $products;
        $this->parser = $parser;
    }

    /**
     * @param string $uuid
     *
     * @return array
     *
     * @throws EntityNotFoundException
     * @throws \InvalidArgumentException
     */
    public function execute(string $uuid): array
    {
        $product = $this->products->getByUuid($uuid);

        return $this->parser->parse($product);
    }
}
