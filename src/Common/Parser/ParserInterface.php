<?php

declare(strict_types=1);

namespace App\Shop\Common\Parser;

interface ParserInterface
{
    public function parse($data): array;
}
