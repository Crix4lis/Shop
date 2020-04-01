<?php

declare(strict_types=1);

namespace App\Shop\Catalogue\UI\Controller;

use App\Shop\Catalogue\Application\Command\CreateNewProductCommand;
use App\Shop\Catalogue\Application\Command\RemoveProductCommand;
use App\Shop\Catalogue\Application\Command\UpdateProductDataCommand;
use App\Shop\Catalogue\Application\Query\GetManyProducts;
use App\Shop\Catalogue\Application\Query\GetProduct;
use App\Shop\Common\Exception\ConflictException;
use App\Shop\Common\Exception\EntityNotFoundException;
use App\Shop\Common\Exception\StorageException;
use App\Shop\Common\Parser\JsonParser;
use App\Shop\Common\Uuid\UuidGeneratorInterface;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductsCatalogueController extends AbstractController
{
    public function getList(Request $request, GetManyProducts $query): JsonResponse
    {
        $page = (int) $request->query->get('page', 1);

        try{
            $data = $query->execute($page);
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['message' => 'Bad method call'], 400);
        }

        return new JsonResponse(['products' => $data, 'page' => $page], 200);
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
            $commandBus->handle(
                new CreateNewProductCommand(
                    $uuid,
                    $data['name'],
                    (float) $data['price_value'],
                    'PLN'
                )
            );
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }

        return new JsonResponse(
            ['message' => 'success'],
            200,
            ['Location' => $this->generateUrl('catalogue_product_get', ['id' => $uuid])]
        );
    }

    public function getSingle(string $id, GetProduct $query): JsonResponse
    {
        try {
            $product = $query->execute($id);
        } catch (EntityNotFoundException $e) {
            return new JsonResponse(
                ['message' => sprintf('Product with uuid %s does not exist', $id)], 404
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }

        return new JsonResponse($product, 200);
    }

    public function delete(string $id, CommandBus $commandBus): JsonResponse
    {
        try {
            $commandBus->handle(new RemoveProductCommand($id));
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        }

        return new JsonResponse(['message' =>'success'], 200);
    }

    public function edit(string $id, Request $request, CommandBus $commandBus, JsonParser $parser)
    {
        $data = $parser->parse($request->getContent());

        if (false === $this->isEditDataValid($data)) {
            return new JsonResponse(['message' => 'Bad method call'], 400);
        }

        $newName = $this->getNewName($data);
        $newPrice = $this->getPrice($data);

        try {
            $commandBus->handle(new UpdateProductDataCommand($id, $newName, $newPrice));
        } catch (StorageException $e) {
            return new JsonResponse(['message' => 'Internal server error'], 500);
        } catch (ConflictException $e) {
            return new JsonResponse(['message' => 'Conflict'], 409);
        }

        return new JsonResponse(['message' => 'success'], 200);
    }

    private function getNewName(array $data): ?string
    {
        if (array_key_exists('new_name', $data)) {
            return $data['new_name'];
        }

        return null;
    }

    private function getPrice(array $data): ?string
    {
        if (array_key_exists('new_price', $data)) {
            return $data['new_price'];
        }

        return null;
    }

    private function isEditDataValid(array $data): bool
    {
        $newNameKey = array_key_exists('new_name', $data);
        $newPriceKey = array_key_exists('new_price', $data);

        if (false ===  $newNameKey &&
            false === $newPriceKey
        ) {
            return false;
        }

        if ($newNameKey !== false && false === is_string($data['new_name'])) {
            return false;
        }

        if ($newPriceKey !== false && false === $this->isFloat($data['new_price'])) {
            return false;
        }

        return true;
    }

    private function isCreateDataValid(array $data): bool
    {
        if (false === array_key_exists('name', $data) || false === is_string($data['name'])) {
            return false;
        }

        if (false === array_key_exists('price_value', $data) || false === $this->isFloat($data['price_value'])) {
            return false;
        }

        if (false === array_key_exists('price_currency', $data) ||
            false === is_string($data['price_currency']) ||
            strtoupper($data['price_currency']) !== 'PLN'
            ) {
            return false;
        }

        return true;
    }

    private function isFloat(string $isFloat): bool
    {
        $float = (float) $isFloat;

        return $float == $isFloat;
    }
}
