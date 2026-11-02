<?php

namespace App\Application\Users\Actions;

use App\Domain\Users\Repositories\UserReadRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetUserByIdAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly UserReadRepositoryInterface $userRepository
    ) {}

    public function execute(int $id): User
    {
        $user = $this->userRepository->findById($id);

        if (!$user) throw (new ModelNotFoundException())->setModel(User::class, [$id]);

        return $user;
    }
}
