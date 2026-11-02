<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Shared\CQRS\QueryBusInterface::class,
            \App\Shared\CQRS\LaravelQueryBus::class
        );

        $this->app->bind(
            \App\Shared\CQRS\CommandBusInterface::class,
            \App\Shared\CQRS\LaravelCommandBus::class
        );

        $this->app->bind(
            \App\Domain\Users\Repositories\UserWriteRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Write\UserWriteRepository::class
        );

        $this->app->bind(
            \App\Domain\Users\Repositories\UserReadRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Read\UserReadRepository::class
        );

        $this->app->bind(
            \App\Domain\Users\Repositories\PropertyWriteRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Write\PropertyWriteRepository::class
        );

        $this->app->bind(
            \App\Domain\Users\Repositories\PropertyReadRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Read\PropertyReadRepository::class
        );

        $this->app->bind(
            \App\Domain\Users\Repositories\CustomerReadRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Read\CustomerReadRepository::class
        );

        $this->app->bind(
            \App\Domain\Users\Repositories\CustomerWriteRepositoryInterface::class,
            \App\Infrastructure\Persistence\Eloquent\Write\CustomerWriteRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
