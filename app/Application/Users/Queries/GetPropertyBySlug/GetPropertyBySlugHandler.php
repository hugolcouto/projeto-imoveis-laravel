<?php

namespace App\Application\Users\Queries\GetPropertyBySlug;

use App\Domain\Users\Repositories\PropertyReadRepositoryInterface;
use App\Models\Property;

class GetPropertyBySlugHandler
{
    public function __construct(
        private readonly PropertyReadRepositoryInterface $propertyReadRepository
    ) {}

    public function handle(GetPropertyBySlugQuery $query): Property
    {
        return $this->propertyReadRepository->findBySlug($query->slug);
    }
}
