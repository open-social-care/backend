<?php

namespace App\Http\Requests\Api\Admin;

use App\Enums\RolesEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OrganizationAssociateUsersRequest extends FormRequest
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
        $organization = $this->route('organization');

        return auth()->user()->can('associateUsers', $organization);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.*.user_id' => 'required|integer|exists:users,id',
            'data.*.role_name' => ['required', 'string', 'exists:roles,name',  Rule::notIn([RolesEnum::ADMIN->value])],
        ];
    }

    public function messages(): array
    {
        return [
            'data.*.role_name.not_in' => 'O Perfil de usuário Admin não pode ser associado a um usuário de organização',
        ];
    }
}
