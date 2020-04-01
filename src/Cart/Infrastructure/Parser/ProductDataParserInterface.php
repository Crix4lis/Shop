<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Parser;

use App\Shop\Catalogue\Domain\Model\Product;

interface ProductDataParserInterface
{
    /**
     * @param Product[] $data
     * @param array     $productsQuantity
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function parse($data, array $productsQuantity): array;
}
