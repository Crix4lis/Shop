<?php

declare(strict_types=1);

namespace App\Shop\Common\Serializer;

class JsonSerializer implements SerializerInterface
{
    public static function serialize(array $data): string
    {
        return json_encode($data);
    }

    public static function deserialize(string $data): array
    {
        return json_decode($data, true);
    }
}
