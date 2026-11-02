<?php

namespace App\Http\Requests\Api\V1\Property;

use App\Rules\TransactionTypeRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'title' => ['required', 'string', 'max:40'],
            'valueInCents' => ['required', 'integer', 'min:0'],
            'description' => ['required', 'string', 'max:255'],
            'amenities.*' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'transactionType' => ['required', new TransactionTypeRule],
        ];
    }
}
