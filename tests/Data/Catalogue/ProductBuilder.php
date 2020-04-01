<?php

declare(strict_types=1);

namespace Tests\Shop\Data\Catalogue;

use App\Shop\Catalogue\Domain\Model\Product;
use App\Shop\Common\Event\DomainEvent;
use App\Shop\Common\Price\Price;
use Tests\Shop\Data\Common\PriceBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class ProductBuilder
{
    private string $uuid;
    private string $name;
    private Price $price;
    /** @var DomainEvent[] */
    private array $events = [];

    public static function product(): self
    {
        return new self();
    }

    public function withUuid(string $uuid): self
    {
        $this->uuid = $uuid;

        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withPriceAsFloat(float $priceValue): self
    {
        $this->price = PriceBuilder::price()->withValue($priceValue)->build();

        return $this;
    }

    public function withEvents(array $events): self
    {
        $this->events = $events;

        return $this;
    }

    public function build(bool $removeEvents = true): Product
    {
        $product = Product::createNew($this->uuid, $this->name, $this->price);
        if ($removeEvents) {
            $product->clearEvents();
        }

        return $product;
    }

    private function __construct()
    {
        $this->uuid = UuidGeneratorMotherObject::createOne();
        $this->name = 'Test Name';
        $this->price = PriceBuilder::price()->withValue(5)->build();
        $this->events = [];
    }
}
