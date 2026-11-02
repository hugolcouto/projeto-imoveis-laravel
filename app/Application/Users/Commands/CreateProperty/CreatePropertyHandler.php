<?php

namespace App\Application\Users\Commands\CreateProperty;

use App\Domain\Users\Repositories\PropertyWriteRepositoryInterface;
use App\Models\Property;
use Illuminate\Support\Facades\DB;

class CreatePropertyHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly PropertyWriteRepositoryInterface $propertyWriteRepository
    ) {}

    public function handle(CreatePropertyCommand $command): Property
    {
        return DB::transaction(function () use ($command): Property {
            $property = $this->propertyWriteRepository->createProperty($command);

            return $property;
        });
    }

}
