<?php

namespace App\Application\Users\Commands\CreateProperty;

use App\Domain\Properties\Enums\TransactionTypeEnum;
use App\Http\Requests\Api\V1\Property\StorePropertyRequest;
use App\Shared\CQRS\CommandInterface;
use Illuminate\Support\Str;

class CreatePropertyCommand implements CommandInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public string $title,
        public string $slug,
        public int $valueInCents,
        public string $description,
        public array $amenities,
        public string $location,
        public TransactionTypeEnum $transactionType,
    ) {}

    public static function fromRequest(StorePropertyRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            $validated['title'],
            Str::slug($validated['title'], '-', 'en'),
            $validated['valueInCents'],
            $validated['description'],
            $validated['amenities'],
            $validated['location'],
            TransactionTypeEnum::from($validated['transactionType']),
        );
    }
}
