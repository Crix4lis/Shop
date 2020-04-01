<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Query;

interface GetCartProducts
{
    public function execute(string $uuid, int $page): array;
}
