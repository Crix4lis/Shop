<?php

declare(strict_types=1);

namespace Tests\Shop\Data\Cart;

use App\Shop\Cart\Domain\Model\Cart;
use App\Shop\Cart\Domain\Model\Product;
use App\Shop\Common\Event\DomainEvent;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class CartBuilder
{
    private string $uuid;
    /** @var Product[]  */
    private array $products = [];
    /** @var DomainEvent[] */
    private array $events = [];

    public static function cart(): self
    {
        return new self();
    }

    public function withProductsAsStringUuids(string... $pUuids): self
    {
        $this->products = [];
        foreach ($pUuids as $uuid) {
            $this->products[] = new Product($uuid);
        }

        return $this;
    }

    public function build(bool $withClearedEvents = true): Cart
    {
        $cart = Cart::createNewCart($this->uuid, ...$this->products);

        if ($withClearedEvents) {
            $cart->clearEvents();
        }

        return $cart;
    }

    private function __construct()
    {
        $this->uuid = UuidGeneratorMotherObject::createOne();
        $this->products = [new Product(UuidGeneratorMotherObject::createOne())];
        $this->events = [];
    }
}
