<?php

declare(strict_types=1);

namespace App\Shop\Common\Uuid;

use Ramsey\Uuid\Uuid;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generateUuidAsString(): string
    {
        return Uuid::uuid4()->toString();
    }
}
