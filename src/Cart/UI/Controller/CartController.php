<?php

declare(strict_types=1);

namespace App\Shop\Cart\UI\Controller;

use App\Shop\Cart\Application\Command\AddProductsToCartCommand;
use App\Shop\Cart\Application\Command\CreateNewCartCommand;
use App\Shop\Cart\Application\Command\RemoveProductFromCartCommand;
use App\Shop\Cart\Application\Query\GetCart;
use App\Shop\Cart\Application\Query\GetCartProducts;
use App\Shop\Cart\Infrastructure\Query\GetAllCartsQuery;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;
use App\Shop\Common\Parser\JsonParser;
use App\Shop\Common\Uuid\UuidGeneratorInterface;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CartController extends AbstractController
{
    /**
     * No pagination, just for me
     * @param GetAllCartsQuery $query
     *
     * @return JsonResponse
     */
    public function getList(GetAllCartsQuery $query)
    {
        $carts = $query->execute();

        return new JsonResponse($carts, 200);
    }

    public function getSingle(string $id, GetCart $getCartQuery): JsonResponse
    {
        try {
            $cart = $getCartQuery->execute($id);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => sprintf('Cart with uuid %s does not exist', $id)], 404);
        }

        return new JsonResponse($cart, 200);
    }

    public function create(
        Request $request,
        CommandBus $commandBus,
        JsonParser $parser,
        UuidGeneratorInterface $generator
    ): JsonResponse
    {
        $data = $parser->parse($request->getContent());

        if (false === $this->isCreateDataValid($data)) {
            return new JsonResponse(['message' => 'Bad method call'], 400);
        }

        $uuid = $generator->generateUuidAsString();

        try {
            $commandBus->handle(new CreateNewCartCommand($uuid, $data['product']));
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['message' => 'Bad method call'], 400);
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }

        return new JsonResponse(
            ['message' => 'success'],
            200,
            ['Location' => $this->generateUrl('cart_get', ['id' => $uuid])]
        );
    }

    public function addProduct(string $id, Request $request, CommandBus $commandBus, JsonParser $parser): JsonResponse
    {
        $data = $parser->parse($request->getContent());

        if (false === $this->isAddProductDataValid($data)) {
            return new JsonResponse(['Bad method call'], 400);
        }

        try {
            $commandBus->handle(new AddProductsToCartCommand($id, ...$data['products']));
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => sprintf('Cart with uuid %s does not exist', $id)], 404);
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }

        return new JsonResponse(['message' => 'success'], 200);
    }

    public function removeProduct(string $id, string $productId, CommandBus $commandBus): JsonResponse
    {
        try {
            $commandBus->handle(new RemoveProductFromCartCommand($id, $productId));
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(['message' => sprintf('Cart with uuid %s does not exist', $id)], 404);
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }

        return new JsonResponse(['message' => 'success'], 200);
    }

    public function getProducts(string $id, Request $request, GetCartProducts $query)
    {
        $page = (int) $request->query->get('page', 1);

        try {
            $data = $query->execute($id, $page);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse([sprintf('Cart with uuid %s does not exist', $id)], 404);
        }

        return new JsonResponse($data, 200);
    }

    private function isCreateDataValid(array $data): bool
    {
        if (false === (array_key_exists('product', $data) && is_string($data['product']))) {
            return false;
        }

        return true;
    }

    private function isAddProductDataValid(array $data): bool
    {
        if (false === array_key_exists('products', $data)) {
            return false;
        }

        if (false === is_array($data['products'])) {
            return false;
        }

        if (empty($data['products'])) {
            return false;
        }

        $products = $data['products'];
        foreach ($products as $item) {
            if (false === is_string($item)) {
                return false;
            }
        }

        return true;
    }
}
