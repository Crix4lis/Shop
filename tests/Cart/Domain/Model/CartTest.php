<?php

declare(strict_types=1);

namespace Tests\Shop\Cart\Domain\Model;

use App\Shop\Cart\Domain\Event\NewCartCreated;
use App\Shop\Cart\Domain\Event\ProductAddedToCart;
use App\Shop\Cart\Domain\Event\ProductRemovedFromCart;
use App\Shop\Cart\Domain\Model\Cart;
use App\Shop\Cart\Domain\Model\Product;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Tests\Shop\AssertDomainEventTrait;
use Tests\Shop\Data\Cart\CartBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class CartTest extends TestCase
{
    use AssertDomainEventTrait;

    public function productsDataDataProvider(): array
    {
        $uuid = UuidGeneratorMotherObject::createOne();
        return [
            'one product' => [$uuid, [$uuid]],
            'two products' => [$uuid, [$uuid, $uuid]],
        ];
    }

    /**
     * @dataProvider productsDataDataProvider
     */
    public function testCreatesNewCartWithProductReferences($uuid, array $pRefs): void
    {
        $products = [];
        foreach ($pRefs as $ref) {
            $products[] = new Product($ref);
        }
        $expectedEvents = new NewCartCreated($uuid, ...$products);

        $cart = Cart::createNewCart($uuid, ...$products);

        $this->assertEquals($uuid, $cart->getUuid());
        $this->assertEquals($products, $cart->getProducts());
        $this->assertEvent([$expectedEvents], $cart->getEvents());
    }

    /**
     * @dataProvider productsDataDataProvider
     */
    public function testCreatesNewCartWithProductsAsArrayCollection($uuid, array $pRefs): void
    {
        $products = [];
        foreach ($pRefs as $ref) {
            $products[] = new Product($ref);
        }
        $expectedEvents = new NewCartCreated($uuid, ...$products);

        $cart = Cart::createNewCart($uuid, new ArrayCollection($products));

        $this->assertEquals($uuid, $cart->getUuid());
        $this->assertEquals($products, $cart->getProducts());
        $this->assertEvent([$expectedEvents], $cart->getEvents());
    }

    /**
     * @dataProvider productsDataDataProvider
     */
    public function testAddsProductsToCart($toAddUuid, array $initialProduct): void
    {
        $cart = CartBuilder::cart()->withProductsAsStringUuids(...$initialProduct)->build();
        $expectedEvents = [new ProductAddedToCart($cart->getUuid(), new Product($toAddUuid))];
        $expectedAll = [];
        foreach ($initialProduct as $p) {
            $expectedAll[] = new Product($p);
        }
        $expectedAll[] = new Product($toAddUuid);

        $cart->addProduct(new Product($toAddUuid));

        $this->assertEquals(UuidGeneratorMotherObject::createOne(), $cart->getUuid());
        $this->assertEquals($expectedAll, $cart->getProducts());
        $this->assertEvent($expectedEvents, $cart->getEvents());
    }

    /**
     * @dataProvider productsDataDataProvider
     */
    public function testRemovesProductsFromCart($toRemove, array $initialProduct): void
    {
        $cart = CartBuilder::cart()->withProductsAsStringUuids(...$initialProduct)->build();
        $expectedEvents = [new ProductRemovedFromCart($cart->getUuid(), new Product($toRemove))];
        $expectedAll = [];
        $removed = 0;
        foreach ($initialProduct as $p) {
            if ($p === $toRemove && $removed === 0) {
                $removed += 1;
                continue;
            }
            $expectedAll[] = new Product($p);
        }

        $cart->removeProduct(new Product($toRemove));

        $this->assertEquals(UuidGeneratorMotherObject::createOne(), $cart->getUuid());
        $this->assertEquals(array_values($expectedAll), array_values($cart->getProducts()));
        $this->assertEvent($expectedEvents, $cart->getEvents());
    }
}
