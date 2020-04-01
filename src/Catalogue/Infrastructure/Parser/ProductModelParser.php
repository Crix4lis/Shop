<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Infrastructure\Parser;

use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Common\Parser\ParserInterface;
use Webmozart\Assert\Assert;

class ProductModelParser implements ParserInterface
{
    /**
     * @param Product $data
     *
     * @return array
     *
     * @throws \InvalidArgumentException
     */
    public function parse($data): array
    {
        Assert::isInstanceOf($data, Product::class);

        return [
            'uuid' => $data->getUuid(),
            'name' => $data->getName(),
            'price_value' => $data->getPrice()->getValue(),
            'price_currency' => $data->getPrice()->getCurrency()
        ];
    }
}
