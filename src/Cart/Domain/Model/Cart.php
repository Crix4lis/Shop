<?php

declare(strict_types=1);

namespace App\Shop\Cart\Domain\Model;

use App\Shop\Cart\Domain\Event\ProductAddedToCart;
use App\Shop\Cart\Domain\Event\NewCartCreated;
use App\Shop\Cart\Domain\Event\ProductRemovedFromCart;
use App\Shop\Common\Event\DomainEvent;
use App\Shop\Common\Event\EventThrowableModel;
use Doctrine\Common\Collections\ArrayCollection;
use Webmozart\Assert\Assert;

class Cart implements EventThrowableModel
{
    private string $uuid;
    /** @var Product[]|ArrayCollection  */
    private $products = [];
    /** @var DomainEvent[] */
    private array $events = [];

    /**
     * @param string                    $uuid
     * @param Product[]|ArrayCollection  $products
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public static function createNewCart(string $uuid, ...$products): self
    {
        if (reset($products) instanceof ArrayCollection) {
            $products = current($products)->toArray();
        }
        self::assertProducts($products);
        Assert::uuid($uuid);
        $newCart = new self($uuid, new ArrayCollection($products));

        $newCart->addEvent(new NewCartCreated($newCart->getUuid(), ...$newCart->getProducts()));

        return $newCart;
    }

    public function addProduct(Product $product): void
    {
        $this->products->add($product);

        $this->addEvent(new ProductAddedToCart($this->getUuid(), $product));
    }

    public function removeProduct(Product $toRemove): void
    {
        foreach ($this->products as $key => $product) {
            if ($product->getProductUuid() === $toRemove->getProductUuid()) {
                $this->products->remove($key);
                $this->addEvent(new ProductRemovedFromCart($this->getUuid(), $product));
                return;
            }
        }
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products->toArray();
    }

    /**
     * @return DomainEvent[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    public function clearEvents(): void
    {
        $this->events = [];
    }

    private function addEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @param string           $uuid
     * @param ArrayCollection  $products
     */
    private function __construct(string $uuid, $products)
    {
        $this->uuid = $uuid;
        $this->products = $products;
    }

    /**
     * @param $products
     *
     * @throws \InvalidArgumentException
     */
    private static function assertProducts($products): void
    {
        Assert::allIsInstanceOf($products, Product::class);
    }
}
