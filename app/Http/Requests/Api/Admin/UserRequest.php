<?php

namespace App\Http\Requests\Api\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class UserRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator): void
    {
        $response = response()->json([
            'type' => 'error',
            'message' => __('messages.common.error_validation_request'),
            'errors' => $validator->errors(),
        ], HttpResponse::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($response);
    }

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
            'organizations' => 'sometimes|nullable|array',
            'organizations.*' => 'sometimes|nullable|exists:organizations,id',
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
