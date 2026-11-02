<?php

namespace App\Shared\CQRS;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): mixed;
}
