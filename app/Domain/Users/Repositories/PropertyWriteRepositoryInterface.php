<?php

namespace App\Domain\Users\Repositories;

use App\Application\Users\Commands\CreateProperty\CreatePropertyCommand;
use App\Models\Property;

interface PropertyWriteRepositoryInterface
{
    public function createProperty(CreatePropertyCommand $command): Property;
}
