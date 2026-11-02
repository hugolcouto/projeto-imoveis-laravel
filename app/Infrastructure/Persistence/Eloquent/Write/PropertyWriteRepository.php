<?php

namespace App\Infrastructure\Persistence\Eloquent\Write;

use App\Application\Users\Commands\CreateProperty\CreatePropertyCommand;
use App\Domain\Users\Repositories\PropertyWriteRepositoryInterface;
use App\Models\Property;

class PropertyWriteRepository implements PropertyWriteRepositoryInterface
{
    public function createProperty(CreatePropertyCommand $command): Property
    {
        $property = new Property([
            'title' => $command->title,
            'slug' => $command->slug,
            'valueInCents' => $command->valueInCents,
            'description' => $command->description,
            'amenities' => $command->amenities,
            'location' => $command->location,
            'transactionType' => $command->transactionType,
        ]);

        $property->save();

        return $property;
    }
}
