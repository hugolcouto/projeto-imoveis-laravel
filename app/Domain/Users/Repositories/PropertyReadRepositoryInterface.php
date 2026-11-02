<?php

namespace App\Domain\Users\Repositories;

use App\Application\Users\Queries\ListProperties\ListPropertiesQuery;
use App\Models\Property;
use Illuminate\Pagination\LengthAwarePaginator;

interface PropertyReadRepositoryInterface
{
    public function findBySlug(string $slug): ?Property;
    public function paginateProperties(ListPropertiesQuery $query): LengthAwarePaginator;
}
