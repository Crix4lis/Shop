<?php

declare(strict_types=1);

namespace Tests\Shop\Data\Common;

use App\Shop\Common\Price\Price;

class PriceBuilder
{
    private float $value;

    public static function price(): self
    {
        return new self();
    }

    public function withValue(float $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function build(): Price
    {
        return Price::createPLN($this->value);
    }

    private function __construct()
    {
        $this->value = 5.5;
    }
}
