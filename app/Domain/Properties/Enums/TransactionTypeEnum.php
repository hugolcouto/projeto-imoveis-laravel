<?php

namespace App\Domain\Properties\Enums;

enum TransactionTypeEnum: string
{
    case RENTAL = 'rental';
    case BUY = 'buy';
}
