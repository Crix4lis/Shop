<?php

declare(strict_types=1);

namespace Tests\Shop\Cart\Domain\Model;

use App\Shop\Cart\Domain\Model\Product;
use PHPUnit\Framework\TestCase;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class ProductTest extends TestCase
{
    public function testCreatesProduct(): void
    {
        $uuid = UuidGeneratorMotherObject::createOne();
        $product = new Product($uuid);

        $this->assertEquals($uuid, $product->getProductUuid());
    }

    public function testTriesTuCratesProductButThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Product('not an uuid');
    }
}
