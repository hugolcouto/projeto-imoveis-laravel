<?php

namespace App\Infrastructure\Persistence\Eloquent\Read;

use App\Application\Users\Queries\ListProperties\ListPropertiesQuery;
use App\Domain\Users\Repositories\PropertyReadRepositoryInterface;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class PropertyReadRepository implements PropertyReadRepositoryInterface
{
    private const READ_COLUMNS = [
        'id',
        'title',
        'slug',
        'valueInCents',
        'description',
        'amenities',
        'location',
        'transactionType',
        'created_at'
    ];

    public function findBySlug(string $slug): ?Property
    {
        return Property::query()
            ->select(self::READ_COLUMNS)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function paginateProperties(ListPropertiesQuery $query): LengthAwarePaginator
    {
        return Property::query()
            ->select(self::READ_COLUMNS)
            ->when($query->search, function (Builder $builder, string $search) {
                $builder->where(function (Builder $subQuery) use ($search) {
                    $subQuery
                        ->where('title', 'ilike', "%{$search}%");
                });
            })
            ->orderBy($query->sortBy, $query->sortDir)
            ->paginate($query->perPage);
    }
}
