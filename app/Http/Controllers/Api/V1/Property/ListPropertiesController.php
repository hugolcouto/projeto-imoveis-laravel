<?php

namespace App\Http\Controllers\Api\V1\Property;

use App\Domain\Properties\Enums\TransactionTypeEnum;
use App\Http\Requests\Api\V1\Property\ListPropertiesRequest;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class ListPropertiesController
{
    /**
     * Create a new class instance.
     */
    public function __invoke(
        ListPropertiesRequest $request
    ): JsonResponse
    {
        $query = Property::query();

        $cacheKey = "properties:list" . md5(http_build_query($request->all()));

        $cached = Cache::remember($cacheKey, 300, function () use ($query, $request) {
            return $query->paginate($request->per_page ?? 10);
        });

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q
                    ->where('title', 'ilike', '%' . $search . '%')
                    ->orWhere('location', 'ilike', '%' . $search . '%');
            });
        }

        if ($request->filled('title')) {
            $query->where('title', 'ilike', '%' . $request->title . '%');
        }

        if ($request->filled('valueInCents')) {
            $query->where('valueInCents', $request->valueInCents);
        }

        if ($request->filled('transactionType')) {
            $query->where('transactionType', $request->transactionType);
        } elseif ($request->has('transactiontype') && $request->transactionType === null) {
            $query->whereIn('transactionType', [TransactionTypeEnum::BUY, TransactionTypeEnum::RENTAL]);
        }

        if ($request->filled('amenities')) {
            foreach ((array) $request->amenities as $amenity) {
                $query->whereJsonContains('amenities', $amenity);
            }
        }

        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');
        $query->orderBy($sortBy, $sortDir);

        $perPage = $request->input('per_page', 15);
        $properties = $query->paginate($perPage);

        return response()->json($properties);
    }
}
