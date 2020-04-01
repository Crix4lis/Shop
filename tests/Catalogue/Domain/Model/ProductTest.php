<?php

declare(strict_types=1);

namespace Tests\Shop\Catalogue\Domain\Model;

use App\Shop\Catalogue\Domain\Event\NewProductCreated;
use App\Shop\Catalogue\Domain\Event\ProductNameChanged;
use App\Shop\Catalogue\Domain\Event\ProductPriceChanged;
use App\Shop\Catalogue\Domain\Model\Product;
use PHPUnit\Framework\TestCase;
use Tests\Shop\AssertDomainEventTrait;
use Tests\Shop\Data\Catalogue\ProductBuilder;
use Tests\Shop\Data\Common\PriceBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class ProductTest extends TestCase
{
    use AssertDomainEventTrait;

    public function productDataProvider(): array
    {
        return [
            'with Data' => [UuidGeneratorMotherObject::createOne(), 'Test Name', 50,],
        ];
    }

    /**
     * @dataProvider productDataProvider
     */
    public function testCreatesProduct($uuid, $name, $priceValue): void
    {
        $price = PriceBuilder::price()->withValue($priceValue)->build();

        $product = Product::createNew($uuid, $name, $price);

        $this->assertEquals($uuid, $product->getUuid());
        $this->assertEquals(ucwords(trim($name)), $product->getName());
        $this->assertEquals($price, $product->getPrice());
        $this->assertEvent([new NewProductCreated($uuid, $product->getName(), $price)], $product->getEvents());
    }

    /**
     * @dataProvider productDataProvider
     */
    public function testChangesName($uuid, $name, $priceValue): void
    {
        $newName = 'New Name';
        $product = ProductBuilder::product()->withUuid($uuid)->withName($name)->withPriceAsFloat($priceValue)->build();

        $product->changeName($newName);

        $this->assertEquals($uuid, $product->getUuid());
        $this->assertEquals(ucwords(trim($newName)), $product->getName());
        $this->assertEquals(PriceBuilder::price()->withValue($priceValue)->build(), $product->getPrice());
        $this->assertEvent([new ProductNameChanged($uuid, $newName)], $product->getEvents());
    }

    /**
     * @dataProvider productDataProvider
     */
    public function testChangesPrice($uuid, $name, $priceValue): void
    {
        $newPrice = PriceBuilder::price()->withValue(1.1)->build();
        $product = ProductBuilder::product()->withUuid($uuid)->withName($name)->withPriceAsFloat($priceValue)->build();

        $product->addNewPrice($newPrice);

        $this->assertEquals($uuid, $product->getUuid());
        $this->assertEquals(ucwords(trim($name)), $product->getName());
        $this->assertEquals($newPrice, $product->getPrice());
        $this->assertEvent([new ProductPriceChanged($uuid, $newPrice)], $product->getEvents());
    }
}
