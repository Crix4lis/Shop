<?php

declare(strict_types=1);

namespace App\Shop\Common\Price;

use Webmozart\Assert\Assert;

class Price
{
    public const PLN = 'PLN';

    private float $value;
    private string $currency;

    /**
     * @param float $value
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public static function createPLN(float $value): self
    {
        self::validateValue($value);

        return new self($value, self::PLN);
    }

    /**
     * @param Price $toAdd
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public function add(Price $toAdd): self
    {
        $this->assertIsTheSameCurrency($toAdd);

        return new self($this->getValue() + $toAdd->getValue(), $this->getCurrency());
    }

    /**
     * @param float $times
     *
     * @return self
     *
     * @throws \InvalidArgumentException
     */
    public function multiply(float $times): self
    {
        Assert::greaterThan($times, 0);

        return new self(round($this->getValue() * $times, 2), $this->getCurrency());
    }

    /**
     * @param Price $asPrice
     *
     * @throws \InvalidArgumentException
     */
    public function assertIsTheSameCurrency(Price $asPrice): void
    {
        Assert::eq($asPrice->getCurrency(), $this->getCurrency(), 'Price currency miss match');
    }

    public function getValue(): float
    {
        return $this->value;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    private function __construct(float $value, string $currency)
    {
        $this->value = $value;
        $this->currency = $currency;
    }

    /**
     * @param float $value
     *
     * @throws \InvalidArgumentException
     */
    private static function validateValue(float $value): void
    {
        Assert::greaterThanEq($value, 0);

        $asStr = (string) $value;
        $exploded = explode('.', $asStr);

        if (array_key_exists(1, $exploded) === false) {
            return;
        }

        Assert::lessThan(strlen($exploded[1]), 3, 'Two decimal places allowed');
    }
}
