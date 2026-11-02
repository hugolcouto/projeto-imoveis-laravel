<?php

namespace App\Application\Users\Commands\CreateCustomer;

use App\Http\Requests\Api\V1\Customer\StoreCustomerRequest;
use App\Shared\CQRS\CommandInterface;
use SensitiveParameter;

class CreateCustomerCommand implements CommandInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $address,
        #[SensitiveParameter]
            public string $password,
    ) {}

    public static function fromRequest(StoreCustomerRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            $validated['name'],
            $validated['email'],
            $validated['phone'],
            $validated['address'],
            $validated['password'],
        );
    }
}
