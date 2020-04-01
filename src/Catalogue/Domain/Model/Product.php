<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Domain\Model;

use App\Shop\Catalogue\Domain\Event\NewProductCreated;
use App\Shop\Catalogue\Domain\Event\ProductNameChanged;
use App\Shop\Catalogue\Domain\Event\ProductPriceChanged;
use App\Shop\Common\Event\DomainEvent;
use App\Shop\Common\Event\EventThrowableModel;
use App\Shop\Common\Price\Price;
use Webmozart\Assert\Assert;

class Product implements EventThrowableModel
{
    private string $uuid;
    private string $name;
    private Price $price;
    /** @var DomainEvent[] */
    private array $events = [];

    public static function createNew(string $uuid, string $name, Price $price): self
    {
        Assert::uuid($uuid);
        $name = ucwords(trim($name));
        $newProduct = new self($uuid, $name, $price);
        $newProduct->addEvent(
            new NewProductCreated($newProduct->getUuid(), $newProduct->getName(), $newProduct->getPrice())
        );

        return $newProduct;
    }

    public function changeName(string $newName): void
    {
        $this->name = $newName;
        $this->addEvent(new ProductNameChanged($this->getUuid(), $this->getName()));
    }

    /**
     * @param Price $newPrice
     *
     * @throws \InvalidArgumentException
     */
    public function addNewPrice(Price $newPrice): void
    {
        $this->price->assertIsTheSameCurrency($newPrice);
        $this->price = $newPrice;
        $this->addEvent(new ProductPriceChanged($this->getUuid(), $this->getPrice()));
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
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

    private function __construct(string $uuid, string $name, Price $price)
    {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->price = $price;
    }

    private function addEvent(DomainEvent $event): void
    {
        $this->events[] = $event;
    }
}
