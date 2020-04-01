<?php

declare(strict_types=1);

namespace App\Shop\Common\Parser;

class JsonParser implements ParserInterface
{
    public function parse($data): array
    {
        return json_decode($data, true);
    }
}
