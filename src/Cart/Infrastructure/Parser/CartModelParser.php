<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Parser;

use App\Shop\Cart\Domain\Model\Cart;
use App\Shop\Common\Parser\ParserInterface;
use Webmozart\Assert\Assert;

class CartModelParser implements ParserInterface
{
    /**
     * @param Cart $data
     *
     * @return array
     */
    public function parse($data): array
    {
        Assert::isInstanceOf($data, Cart::class);

        $parsedProducts = [];
        $products = $data->getProducts();
        foreach($products as $p) {
            if (array_key_exists($p->getProductUuid(), $parsedProducts) !== false) {
                $parsedProducts[$p->getProductUuid()] += 1;

                continue;
            }

            $parsedProducts[$p->getProductUuid()] = 1;
        }

        $parsedCart['cart_uuid'] = $data->getUuid();
        $parsedCart['products'] = [];

        foreach($parsedProducts as $productuuid => $quantity) {
            $parsedCart['products'][] = ['product_uuid' => $productuuid, 'quantity' => $quantity];
        }

        return $parsedCart;
    }
}
