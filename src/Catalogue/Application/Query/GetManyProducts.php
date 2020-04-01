<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Query;

interface GetManyProducts
{
    /**
     * @param int $page
     * @param int $perPage
     *
     * @return array
     */
    public function execute(int $page, int $perPage = 3): array;
}
