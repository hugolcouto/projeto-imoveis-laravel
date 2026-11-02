<?php

namespace App\Application\Users\Commands\CreateAdmin;

use App\Domain\Users\Enums\UserRoleEnum;
use App\Http\Requests\Api\V1\Admin\StoreAdminRequest;
use App\Shared\CQRS\CommandInterface;
use SensitiveParameter;

class CreateAdminCommand implements CommandInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        #[SensitiveParameter]
            public string $password,
        public UserRoleEnum $role,
    )
    {}

    public static function fromRequest(StoreAdminRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            $validated['name'],
            $validated['email'],
            $validated['password'],
            UserRoleEnum::from($validated['role'])
        );
    }
}
