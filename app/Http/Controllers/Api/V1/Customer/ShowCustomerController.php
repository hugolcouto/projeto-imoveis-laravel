<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Application\Users\Queries\GetCustomerById\GetCustomerByIdQuery;
use App\Http\Resources\Api\V1\CustomerResource;
use App\Shared\CQRS\QueryBusInterface;

class ShowCustomerController
{
    public function __invoke(
        int $id,
        QueryBusInterface $queryBus
    )
    {
        $customer = $queryBus->ask(new GetCustomerByIdQuery(userId: $id));

        return new CustomerResource($customer);
    }
}