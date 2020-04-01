<?php

declare(strict_types=1);

namespace Tests\Shop\Data\Common;

class UuidGeneratorMotherObject
{
    public static function createOne(): string
    {
        return '7cd5ef58-fee0-4ada-a8ad-ee3e1f58de76';
    }

    public static function createOneSecond(): string
    {
        return '8cd5ef58-fee0-4ada-a8ad-ee3e1f58de76';
    }

    public static function createOneThird(): string
    {
        return '9cd5ef58-fee0-4ada-a8ad-ee3e1f58de76';
    }
}
