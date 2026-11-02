<?php

namespace App\Application\Users\Commands\CreateCustomer;

use App\Domain\Users\Repositories\CustomerWriteRepositoryInterface;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class CreateCustomerHandler
{
    public function __construct(
        public CustomerWriteRepositoryInterface $customerWriteRepository
    ) {}

    public function handle(CreateCustomerCommand $command): Customer
    {
        return DB::transaction(function () use ($command): Customer {
            $customer = $this->customerWriteRepository->createCustomer($command);

            return $customer;
        });
    }
}
