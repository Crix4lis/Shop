<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Query;

interface GetProduct
{
    public function execute(string $uuid): array;
}
