<?php

namespace App\Shared\CQRS;

use Illuminate\Contracts\Container\Container;
use RuntimeException;

class LaravelQueryBus implements QueryBusInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly Container $container
    )
    {
        //
    }

    public function ask(QueryInterface $query): mixed
    {
        $handlerClass = preg_replace('/Query$/', 'Handler', $query::class);

        if (!class_exists($handlerClass))
            throw new RuntimeException("Handler [{$handlerClass}] não encontrado para o Query [" . $query::class . "]");

        $handler = $this->container->make($handlerClass);

        return $handler->handle($query);
    }
}
