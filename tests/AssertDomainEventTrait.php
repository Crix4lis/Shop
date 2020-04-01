<?php

declare(strict_types=1);

namespace Tests\Shop;

use App\Shop\Common\Event\DomainEvent;

trait AssertDomainEventTrait
{
    /**
     * @param DomainEvent[] $expected
     * @param DomainEvent[] $productEvents
     */
    private function assertEvent(array $expected, array $productEvents)
    {
        $this->assertCount(count($expected), $productEvents);
        foreach ($expected as $k => $e) {
            $this->assertEquals($e->getTypeName(), $productEvents[$k]->getTypeName());
            $this->assertEquals($e->getEventBody(), $productEvents[$k]->getEventBody());
        }
    }
}
