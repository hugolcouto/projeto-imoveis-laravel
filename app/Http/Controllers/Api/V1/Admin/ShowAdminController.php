<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Application\Users\Queries\GetAdminById\GetAdminByIdQuery;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Shared\CQRS\QueryBusInterface;

class ShowAdminController extends Controller
{
    public function __invoke(
        int $id,
        QueryBusInterface $queryBus
    )
    {
        $user = $queryBus->ask(new GetAdminByIdQuery(userId: $id));
        return new UserResource($user);
    }
}
