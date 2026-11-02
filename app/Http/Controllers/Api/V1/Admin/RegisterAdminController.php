<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Application\Users\Commands\CreateAdmin\CreateAdminCommand;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Admin\StoreAdminRequest;
use App\Http\Resources\Api\V1\UserResource;
use App\Shared\CQRS\CommandBusInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class RegisterAdminController extends Controller
{
    public function __invoke(
        StoreAdminRequest $request,
        CommandBusInterface $commandBus
    ): JsonResponse
    {
        $command = CreateAdminCommand::fromRequest($request);

        $admin = $commandBus->dispatch($command);

        return (new UserResource($admin))->response()->setStatusCode(Response::HTTP_CREATED);
    }
}
