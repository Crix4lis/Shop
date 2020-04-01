<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Query;

use App\Shop\Cart\Domain\Model\CartsInterface;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Parser\ParserInterface;

class GetAllCartsQuery
{
    private CartsInterface $carts;
    private ParserInterface $parser;

    public function __construct(
        CartsInterface $carts,
        ParserInterface $parser
    ) {
        $this->carts = $carts;
        $this->parser = $parser;
    }

    /**
     * @return array
     *
     * @throws EntityNotFoundException
     */
    public function execute(): array
    {
        $carts = $this->carts->getAll();

        $data = [];
        foreach ($carts as $cart) {
            $data[] = $this->parser->parse($cart);
        }

        return $data;
    }
}
