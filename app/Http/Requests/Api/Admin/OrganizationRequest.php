<?php

namespace App\Http\Requests\Api\Admin;

use App\Enums\DocumentTypesEnum;
use App\Models\Organization;
use App\Support\DocumentValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class OrganizationRequest extends FormRequest
{
    public function __construct()
    {
        $this->registerCustomValidations();
    }

    /**
     * Handle a failed validation attempt.
     *
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
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

        return $organization
            ? auth()->user()->can('update', $organization)
            : auth()->user()->can('create', Organization::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $documentTypes = array_column(DocumentTypesEnum::cases(), 'value');

        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|phone',
            'document_type' => ['required', 'string', Rule::in($documentTypes)],
            'document' => 'required|string|document',
            'subject_ref' => 'sometimes|nullable|string|max:255',
        ];
    }

    private function registerCustomValidations(): void
    {
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            if (! $value) {
                return true;
            }

            $phoneRegex = '/^\([0-9]{2}\)\s[0-9]{4,5}-[0-9]{4}$/';
            $matchs = preg_match($phoneRegex, $value);

            return $matchs > 0;
        });

        Validator::extend('document', function ($attribute, $value, $parameters, $validator) {
            $documentType = $this->get('document_type');

            if ($documentType == DocumentTypesEnum::CNPJ->value) {
                return DocumentValidator::validateCnpj($value);
            } elseif ($documentType == DocumentTypesEnum::CPF->value) {
                return DocumentValidator::validateCpf($value);
            }

            return false;
        });
    }
}
