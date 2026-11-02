<?php

namespace App\Shared\CQRS;

use Illuminate\Contracts\Container\Container;
use RuntimeException;

class LaravelCommandBus implements CommandBusInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        private readonly Container $container
    ) {}
    public function dispatch(CommandInterface $command): mixed
    {
        $handlerClass = preg_replace('/Command$/', 'Handler', $command::class);

        if (!class_exists($handlerClass))
            throw new RuntimeException("Handler [{$handlerClass}] não encontrado para o Command [" . $command::class . "]");

        $handler = $this->container->make($handlerClass);

        return $handler->handle($command);
    }
}
