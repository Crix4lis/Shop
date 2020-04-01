<?php

declare(strict_types=1);

namespace Tests\Shop\Cart\Infrastructure\Parser;

use App\Shop\Cart\Infrastructure\Parser\ProductDataParser;
use PHPUnit\Framework\TestCase;
use Tests\Shop\Data\Catalogue\ProductBuilder;
use Tests\Shop\Data\Common\PriceBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class ProductDataParserTest extends TestCase
{
    public function testParsesTwoModels(): void
    {
        $uuid1 = UuidGeneratorMotherObject::createOne();
        $uuid2 = UuidGeneratorMotherObject::createOneSecond();
        $name1 = "Product 1";
        $name2 = "Product 2";
        $value1 = 5.5;
        $value2 = 10;

        $productsQuantity[$uuid1] = 1;
        $productsQuantity[$uuid2] = 2;
        $models[] = ProductBuilder::product()
            ->withUuid($uuid1)
            ->withName($name1)
            ->withPriceAsFloat($value1)
            ->build();
        $models[] = ProductBuilder::product()
            ->withUuid($uuid2)
            ->withName($name2)
            ->withPriceAsFloat($value2)
            ->build();

        $expected = [
            'products' => [
                [
                    'product_uuid' => $uuid1,
                    'product_name' => $name1,
                    'product_price_value' => $value1,
                    'product_price_currency' => 'PLN',
                    'quantity' => 1
                ],
                [
                    'product_uuid' => $uuid2,
                    'product_name' => $name2,
                    'product_price_value' => $value2,
                    'product_price_currency' => 'PLN',
                    'quantity' => 2
                ],
            ],
            'currentCartValue' => PriceBuilder::price()->withValue($value1 + ($value2 * 2))->build()
        ];

        $parsed = (new ProductDataParser())->parse($models, $productsQuantity);

        $this->assertEquals($expected, $parsed);
    }

    public function testParsesNoModels(): void
    {
        $expected = [
            'products' => [],
            'currentCartValue' => null,
        ];

        $parsed = (new ProductDataParser())->parse([], []);

        $this->assertEquals($expected, $parsed);
    }
}
