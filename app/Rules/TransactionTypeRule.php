<?php

namespace App\Rules;

use App\Domain\Properties\Enums\TransactionTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;

class TransactionTypeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        $validTypes = array_map(fn ($case) => $case->value, TransactionTypeEnum::cases());

        if (!in_array($value, $validTypes, true)) {
            $fail("O campo '{$attribute}' deve ser um tipo de transação válida.");
        }
    }
}
