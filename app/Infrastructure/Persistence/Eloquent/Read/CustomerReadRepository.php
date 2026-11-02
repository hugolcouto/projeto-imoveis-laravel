<?php

namespace App\Infrastructure\Persistence\Eloquent\Read;

use App\Domain\Users\Repositories\CustomerReadRepositoryInterface;
use App\Models\Customer;

class CustomerReadRepository implements CustomerReadRepositoryInterface
{
    private const READ_COLUMNS = ['id', 'name', 'email', 'phone', 'address', 'role'];

    public function findById(int $id): ?Customer
    {
        return Customer::query()
            ->select(self::READ_COLUMNS)
            ->find($id);
    }
}