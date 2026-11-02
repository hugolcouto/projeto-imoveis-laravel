<?php

namespace App\Infrastructure\Persistence\Eloquent\Read;

use App\Domain\Users\Repositories\UserReadRepositoryInterface;
use App\Models\User;

class UserReadRepository implements UserReadRepositoryInterface
{
    private const READ_COLUMNS = ['id', 'name', 'email', 'role', 'created_at'];

    public function findById(int $id): ?User
    {
        return User::query()
            ->select(self::READ_COLUMNS)
            ->find($id);
    }
}
