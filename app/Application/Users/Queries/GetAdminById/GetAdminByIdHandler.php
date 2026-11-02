<?php

namespace App\Application\Users\Queries\GetAdminById;

use App\Domain\Users\Repositories\UserReadRepositoryInterface;
use App\Models\User;

class GetAdminByIdHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly UserReadRepositoryInterface $userReadRepository
    ) {}

    public function handle(GetAdminByIdQuery $query): User
    {
        $user = $this->userReadRepository->findById($query->userId);
        return $user;
    }
}
