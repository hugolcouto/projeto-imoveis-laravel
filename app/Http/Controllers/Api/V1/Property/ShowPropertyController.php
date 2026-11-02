<?php

namespace App\Http\Controllers\Api\V1\Property;

use App\Application\Users\Queries\GetPropertyBySlug\GetPropertyBySlugQuery;
use App\Http\Resources\Api\V1\PropertyResource;
use App\Shared\CQRS\QueryBusInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ShowPropertyController
{
    public function __invoke(
        string $slug,
        QueryBusInterface $queryBus,
        Request $request
    )
    {
        $cacheKey = "property:slug:" . $slug;

        $data = Cache::remember($cacheKey, 3600, function () use ($slug, $queryBus, $request) {
            $property = $queryBus->ask(new GetPropertyBySlugQuery(slug: $slug));

            return (new PropertyResource($property))->resolve($request);
        });

        return response()->json(['data' => $data]);
    }
}
