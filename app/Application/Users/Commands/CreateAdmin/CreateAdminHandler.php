<?php

namespace App\Application\Users\Commands\CreateAdmin;

use App\Domain\Users\Repositories\UserWriteRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CreateAdminHandler
{
    public function __construct(
        private readonly UserWriteRepositoryInterface $userWriteRepository
    )
    {}

    public function handle(CreateAdminCommand $command): User
    {
        return DB::transaction(function () use ($command): User {
            $user = $this->userWriteRepository->createAdmin($command);

            return $user;
        });
    }

}

