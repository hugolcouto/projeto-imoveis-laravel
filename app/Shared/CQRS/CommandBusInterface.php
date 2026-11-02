<?php

namespace App\Shared\CQRS;

interface CommandBusInterface
{
    public function dispatch(CommandInterface $command): mixed;
}
