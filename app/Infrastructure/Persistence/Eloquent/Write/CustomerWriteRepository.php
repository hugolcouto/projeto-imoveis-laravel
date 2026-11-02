<?php

namespace App\Infrastructure\Persistence\Eloquent\Write;

use App\Application\Users\Commands\CreateCustomer\CreateCustomerCommand;
use App\Domain\Users\Repositories\CustomerWriteRepositoryInterface;
use App\Models\Customer;

class CustomerWriteRepository implements CustomerWriteRepositoryInterface
{ public function createCustomer(CreateCustomerCommand $command): Customer
    {
        $customer = new Customer([
            'name' => $command->name,
            'email' => $command->email,
            'phone' => $command->phone,
            'address' => $command->address,
            'password' => $command->password,
        ]);

        $customer->save();

        return $customer;
    }
}
