<?php

namespace App\Domain\Users\Repositories;

use App\Application\Users\Commands\CreateAdmin\CreateAdminCommand;
use App\Models\User;

interface UserWriteRepositoryInterface
{
    public function createAdmin(CreateAdminCommand $command): User;
}
