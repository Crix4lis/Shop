<?php

declare(strict_types=1);

namespace App\Shop\Common\Uuid;

interface UuidGeneratorInterface
{
    public function generateUuidAsString(): string;
}
