<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Application\Users\Commands\CreateCustomer\CreateCustomerCommand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\StoreCustomerRequest;
use App\Http\Resources\Api\V1\CustomerResource;
use App\Shared\CQRS\CommandBusInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterCustomerController extends Controller
{
    public function __invoke(
        StoreCustomerRequest $request,
        CommandBusInterface $commandBus
    ): JsonResponse
    {
        $command = CreateCustomerCommand::fromRequest($request);

        $customer = $commandBus->dispatch($command);

        return (new CustomerResource($customer))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }
}
