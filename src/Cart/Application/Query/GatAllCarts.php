<?php

declare(strict_types=1);

namespace App\Shop\Cart\Application\Query;

interface GatAllCarts
{
    public function execute(): array;
}
