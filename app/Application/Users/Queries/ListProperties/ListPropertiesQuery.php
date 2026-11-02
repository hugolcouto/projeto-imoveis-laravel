<?php

namespace App\Application\Users\Queries\ListProperties;

use App\Domain\Properties\Enums\TransactionTypeEnum;
use App\Http\Requests\Api\V1\Property\ListPropertiesRequest;
use App\Shared\CQRS\QueryInterface;

class ListPropertiesQuery implements QueryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $search = null,
        public TransactionTypeEnum $transactionType = TransactionTypeEnum::BUY,
        public int $perPage = 10,
        public string $sortBy = 'created_at',
        public string $sortDir = 'desc'
    ) {}

    public static function fromRequest(ListPropertiesRequest $request): self
    {
        $validated = $request->validated();

        return new self(
            search: isset($validated['search']) ? trim($validated['search']) : null,
            transactionType: TransactionTypeEnum::BUY,
            perPage: (int) ($validated['per_page'] ?? 10),
            sortBy: $validated['sort_by'] ?? 'created_at',
            sortDir: $validated['sort_dir'] ?? 'desc'
        );
    }
}
