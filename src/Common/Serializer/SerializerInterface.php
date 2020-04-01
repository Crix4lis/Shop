<?php

declare(strict_types=1);

namespace App\Shop\Common\Serializer;

interface SerializerInterface
{
    public static function serialize(array $data): string;
    public static function deserialize(string $data): array;
}
