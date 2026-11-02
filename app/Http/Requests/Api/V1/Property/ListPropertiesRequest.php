<?php

namespace App\Http\Requests\Api\V1\Property;

use App\Domain\Properties\Enums\TransactionTypeEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListPropertiesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:100'],
            'title' => ['nullable', 'string', 'max:40'],
            'valueInCents' => ['nullable', 'integer', 'min:0'],
            'transactionType' => ['nullable', function ($attribute, $value, $fail) {
                if ($value != null && !in_array($value, [TransactionTypeEnum::BUY, TransactionTypeEnum::RENTAL])) {
                    $fail("O campo transactionType deve ser buy, rental ou omitido");
                }
            }],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['string'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sort_by' => ['nullable', 'string', Rule::in(['title', 'valueInCents', 'transactionType', 'created_at'])],
            'sort_dir' => ['nullable', 'string', Rule::in(['asc', 'desc'])],
        ];
    }
}
