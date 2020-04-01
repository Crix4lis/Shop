<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Parser;

use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Common\Price\Price;
use Webmozart\Assert\Assert;

class ProductDataParser implements ProductDataParserInterface
{
    /**
     * @param Product[] $data
     * @param array     $productsQuantity ['productUuid' => quantity]
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function parse($data, array $productsQuantity): array
    {
        Assert::allIsInstanceOf($data, Product::class);
        Assert::allInteger($productsQuantity);

        $currentCartValue = null;
        $parsed = [];
        foreach ($data as $product) {
            $parsed['products'][] = [
                'product_uuid' => $product->getUuid(),
                'product_name' => $product->getName(),
                'product_price_value' => $product->getPrice()->getValue(),
                'product_price_currency' => $product->getPrice()->getCurrency(),
                'quantity' => $productsQuantity[$product->getUuid()],
            ];
            //so far only PLN
            $currentCartValue = $currentCartValue === null ?
                $this->countPrice(
                    $product->getPrice(),
                    $productsQuantity[$product->getUuid()]
                ) :
                $this->countPrice(
                    $product->getPrice(),
                    $productsQuantity[$product->getUuid()]
                )->add($currentCartValue);
        }

        if (false == array_key_exists('products', $parsed)) {
            $parsed['products'] = [];
        }
        $parsed['currentCartValue'] = $currentCartValue;

        return $parsed;
    }

    private function countPrice(Price $productPrice, int $productQuantity): Price
    {
        return $productPrice->multiply($productQuantity);
    }
}
