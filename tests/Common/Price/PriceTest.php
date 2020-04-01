<?php

declare(strict_types=1);

namespace Tests\Shop\Common\Price;

use App\Shop\Common\Price\Price;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function validPriceValueDataProvider(): array
    {
        return [
            'with precistion 0' => [20, (float) 20],
            'with value 0' => [0, (float) 0],
            'with precision 1' => [20.1, (float) 20.1],
            'with precision 2' => [20.22, (float) 20.22]
        ];
    }

    public function invalidPriceValueDataProvider(): array
    {
        return [
            'less than 0' => [-1],
            'with precision over 2' => [1.111],
        ];
    }

    public function addValesDataProvider(): array
    {
        return [
            'adds 0' => [5, 0, 5],
            'with precision 2' => [5, 2.55, 7.55],
        ];
    }

    public function multiplyValesDataProvider(): array
    {
        return [
            'natural number' => [5, 3, 15],
            'with not integer' => [5, 0.5, 2.5]
        ];
    }

    public function invalidMultiplierValesDataProvider(): array
    {
        return [
            'natural number' => [5, 0],
            'with not integer' => [5, -1]
        ];
    }

    /**
     * @dataProvider validPriceValueDataProvider
     */
    public function testCreatesPrice($value, $expected): void
    {
        $price = Price::createPLN($value);

        $this->assertEquals($expected, $price->getValue());
        $this->assertEquals('PLN', $price->getCurrency());
    }

    /**
     * @dataProvider invalidPriceValueDataProvider
     */
    public function testThrowsExceptionWhenTriesToCreateValue($value): void
    {
        $this->expectException(\InvalidArgumentException::class);

        Price::createPLN($value);
    }

    /**
     * @dataProvider addValesDataProvider
     */
    public function testAddsPrices($vale1, $value2, $expected): void
    {
        $price1 = Price::createPLN($vale1);
        $price2 = Price::createPLN($value2);
        $result = $price1->add($price2);

        $this->assertEquals($expected, $result->getValue());
    }

    /**
     * @dataProvider multiplyValesDataProvider
     */
    public function testMultipliesPrice($value, $multiply, $expected): void
    {
        $price = Price::createPLN($value);
        $result = $price->multiply($multiply);

        $this->assertEquals($expected, $result->getValue());
    }

    /**
     * @dataProvider invalidMultiplierValesDataProvider
     */
    public function testTriesToMultiplyWithInvalidValuePrice($value, $multiply): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $price = Price::createPLN($value);
        $price->multiply($multiply);
    }
}
