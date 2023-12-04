<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => 'required|string|min:6|confirmed',
            'roles' => 'required|array',
            'roles.*' => 'required|exists:roles,id',
            'organizations' => 'required|array',
            'organizations.*' => 'required|exists:organizations,id',
        ];

        if ($this->method() === 'PUT' && $this->user) {
            $rules['email'][] = Rule::unique('users')
                ->whereNot('id', $this->user->id)
                ->whereNull('deleted_at');

            $rules['password'] = 'nullable|string|min:6|confirmed';
        } else {
            $rules['email'][] = Rule::unique('users')
                ->whereNull('deleted_at');
        }

        return $rules;
    }
}
