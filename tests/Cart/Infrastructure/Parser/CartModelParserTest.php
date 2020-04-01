<?php

declare(strict_types=1);

namespace Tests\Shop\Cart\Infrastructure\Parser;

use App\Shop\Cart\Infrastructure\Parser\CartModelParser;
use PHPUnit\Framework\TestCase;
use Tests\Shop\Data\Cart\CartBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class CartModelParserTest extends TestCase
{
    public function testParsesCartModel(): void
    {
        $model = CartBuilder::cart()
            ->withProductsAsStringUuids(...[
                UuidGeneratorMotherObject::createOne(),
                UuidGeneratorMotherObject::createOne(),
                UuidGeneratorMotherObject::createOneSecond(),
                UuidGeneratorMotherObject::createOneThird(),
            ])
            ->build();
        $expected = [
            'cart_uuid' => UuidGeneratorMotherObject::createOne(),
            'products' => [
                [
                    'product_uuid' => uuidgeneratormotherobject::createone(),
                    'quantity' => 2
                ],
                [
                    'product_uuid' => uuidgeneratormotherobject::createOneSecond(),
                    'quantity' => 1
                ],
                [
                    'product_uuid' => uuidgeneratormotherobject::createOneThird(),
                    'quantity' => 1
                ],
            ],
        ];

        $rest = (new CartModelParser())->parse($model);

        $this->assertEquals($expected, $rest);
    }
}
