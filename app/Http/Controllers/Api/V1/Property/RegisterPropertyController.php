<?php

namespace App\Http\Controllers\Api\V1\Property;

use App\Application\Users\Commands\CreateProperty\CreatePropertyCommand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Property\StorePropertyRequest;
use App\Http\Resources\Api\V1\PropertyResource;
use App\Shared\CQRS\CommandBusInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterPropertyController extends Controller
{
    public function __invoke(
        StorePropertyRequest $request,
        CommandBusInterface $commandBus
    ): JsonResponse
    {
        $command = CreatePropertyCommand::fromRequest($request);

        $property = $commandBus->dispatch($command);

        return (new PropertyResource($property))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
