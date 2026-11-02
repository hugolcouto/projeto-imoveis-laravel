<?php

namespace App\Application\Users\Queries\GetPropertyBySlug;

use App\Shared\CQRS\QueryInterface;

class GetPropertyBySlugQuery implements QueryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(public string $slug) {}
}
