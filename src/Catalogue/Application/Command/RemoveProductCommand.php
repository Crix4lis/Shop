<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\Application\Command;

class RemoveProductCommand
{
    private string $uuidToRemove;

    public function __construct($uuidToRemove)
    {
        $this->uuidToRemove = $uuidToRemove;
    }

    public function getUuidToRemove()
    {
        return $this->uuidToRemove;
    }
}
