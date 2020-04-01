<?php

declare(strict_types=1);

namespace Tests\Shop\Catalogue\Infrastructure\Parser;

use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Catalogue\Infrastructure\Parser\ProductModelParser;
use PHPUnit\Framework\TestCase;
use Tests\Shop\Data\Common\PriceBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class ProductModelParserTest extends TestCase
{
    public function testParserProductModel(): void
    {
        $uuid = UuidGeneratorMotherObject::createOne();
        $name = 'Product Name';
        $priceValue = 10;
        $expected = [
            'uuid' => $uuid,
            'name' => $name,
            'price_value' => $priceValue,
            'price_currency' => 'PLN'
        ];
        $model = Product::createNew($uuid, $name, PriceBuilder::price()->withValue($priceValue)->build());

        $result = (new ProductModelParser())->parse($model);

        $this->assertEquals($expected, $result);
    }
}
