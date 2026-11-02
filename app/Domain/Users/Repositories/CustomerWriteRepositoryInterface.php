<?php

namespace App\Domain\Users\Repositories;

use App\Application\Users\Commands\CreateCustomer\CreateCustomerCommand;
use App\Models\Customer;

interface CustomerWriteRepositoryInterface
{
    public function createCustomer(CreateCustomerCommand $command): Customer;
}
