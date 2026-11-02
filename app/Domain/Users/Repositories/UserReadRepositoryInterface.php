<?php

namespace App\Domain\Users\Repositories;

use App\Models\User;

interface UserReadRepositoryInterface
{
    public function findById(int $id): ?User;
}
