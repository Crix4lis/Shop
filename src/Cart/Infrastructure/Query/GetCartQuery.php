<?php

declare(strict_types=1);

namespace App\Shop\Cart\Infrastructure\Query;

use App\Shop\Cart\Application\Query\GetCart;
use App\Shop\Cart\Domain\Model\CartsInterface;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Parser\ParserInterface;

class GetCartQuery implements GetCart
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
     * @param string $uuid
     *
     * @return array
     *
     * @throws EntityNotFoundException
     */
    public function execute(string $uuid): array
    {
        $cart = $this->carts->getByUuid($uuid);

        return $this->parser->parse($cart);
    }
}
