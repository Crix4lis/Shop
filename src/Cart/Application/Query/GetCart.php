<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Query;

interface GetCart
{
    public function execute(string $uuid): array;
}
