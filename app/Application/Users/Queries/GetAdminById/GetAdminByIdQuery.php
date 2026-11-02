<?php

namespace App\Application\Users\Queries\GetAdminById;

use App\Shared\CQRS\QueryInterface;

class GetAdminByIdQuery implements QueryInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(public int $userId) {}
}
