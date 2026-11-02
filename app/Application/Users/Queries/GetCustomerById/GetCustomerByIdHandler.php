<?php

namespace App\Application\Users\Queries\GetCustomerById;

use App\Domain\Users\Repositories\CustomerReadRepositoryInterface;
use App\Models\Customer;

class GetCustomerByIdHandler
{
    public function __construct(
        private readonly CustomerReadRepositoryInterface $customerReadRepository
    ) {}

    public function handle(GetCustomerByIdQuery $query): Customer
    {
        $customer = $this->customerReadRepository->findById($query->userId);
        return $customer;
    }
}