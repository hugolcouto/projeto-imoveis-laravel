<?php

namespace App\Domain\Users\Enums;

enum UserRoleEnum: string
{
    case ADMIN = "admin";
    case BROKER = "broker";
    case OWNER = "owner";
    case CUSTOMER = "customer";
}
