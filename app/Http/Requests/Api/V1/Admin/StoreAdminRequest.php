<?php

namespace App\Http\Requests\Api\V1\Admin;

use App\Domain\Users\Enums\UserRoleEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Aqui pode ser validado se quem faz a requisição tem permissão,
        // ou true caso seja uma rota inicial protegida por middleware/token de setup.

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
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(6)->mixedCase()->numbers()->symbols()],
            'role' => ['required', 'string', Rule::in([UserRoleEnum::ADMIN->value])]
        ];
    }
}
