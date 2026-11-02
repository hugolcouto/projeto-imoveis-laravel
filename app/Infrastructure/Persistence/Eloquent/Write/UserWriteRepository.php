<?php

namespace App\Infrastructure\Persistence\Eloquent\Write;

use App\Application\Users\Commands\CreateAdmin\CreateAdminCommand;
use App\Domain\Users\Repositories\UserWriteRepositoryInterface;
use App\Models\User;

class UserWriteRepository implements UserWriteRepositoryInterface
{
    public function createAdmin(CreateAdminCommand $command): User
    {
        $user = new User([
            'name' => $command->name,
            'email' => $command->email,
            'password' => $command->password,
            'role' => $command->role
        ]);

        // Executa o INSERT no PostgreSQL aplicando os casts do Model User
        $user->save();

        return $user;
    }
}
