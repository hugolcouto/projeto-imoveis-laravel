<?php

namespace App\Application\Users\Queries\GetCustomerById;

use App\Shared\CQRS\QueryInterface;

class GetCustomerByIdQuery implements QueryInterface
{
    public function __construct(public int $userId) {}
}