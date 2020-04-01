<?php

declare(strict_types=1);

namespace Tests\Shop\Common\Event;

use App\Shop\Catalogue\Domain\Event\NewProductCreated;
use PHPUnit\Framework\TestCase;
use Tests\Shop\Data\Common\PriceBuilder;
use Tests\Shop\Data\Common\UuidGeneratorMotherObject;

class DoctrineDomainEventTest extends TestCase
{
    public function testCreatesEventModelFromSpecifiedEvent(): void
    {
        $uuid = UuidGeneratorMotherObject::createOne();
        $name = 'Product Name';
        $value = 10;
        $price = PriceBuilder::price()->withValue($value)->build();

        $createdNewProduct = new NewProductCreated($uuid, $name, $price);
        $model = $createdNewProduct->getModel();

        $this->assertEquals($createdNewProduct->getEventBody(), $model->getEventBody());
        $this->assertEquals($createdNewProduct->getOccurredOn(), $model->getOccurredOn());
        $this->assertEquals($createdNewProduct->getTypeName(), $model->getTypeName());
    }
}
