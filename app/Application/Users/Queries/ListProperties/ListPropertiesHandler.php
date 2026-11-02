<?php

namespace App\Application\Users\Queries\ListProperties;

use App\Domain\Users\Repositories\PropertyReadRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ListPropertiesHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly PropertyReadRepositoryInterface $propertyReadRepository
    ) {}

    public function handle(ListPropertiesQuery $query): LengthAwarePaginator
    {
        return $this->propertyReadRepository->paginateProperties($query);
    }
}
