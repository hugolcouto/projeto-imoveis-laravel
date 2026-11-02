<?php

namespace App\Domain\Users\Repositories;

use App\Models\Customer;

interface CustomerReadRepositoryInterface
{
    public function findById(int $id): ?Customer;
}