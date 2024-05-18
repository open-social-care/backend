<?php

namespace App\Http\Requests\Api\SocialAssistant;

use App\Enums\SkinColorsEnum;
use App\Models\Subject;
use App\Support\DocumentValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class SubjectCreateRequest extends FormRequest
{
    public function __construct()
    {
        parent::__construct();
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

        return auth()->user()->can('createByOrganization', [Subject::class, $organization]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $skinColors = array_column(SkinColorsEnum::cases(), 'value');

        $rules = [
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'nationality' => 'sometimes|nullable|string|max:255',
            'phone' => 'sometimes|nullable|phone',
            'father_name' => 'sometimes|nullable|string|max:255',
            'mother_name' => 'sometimes|nullable|string|max:255',
            'cpf' => 'sometimes|nullable|string|cpf',
            'rg' => 'sometimes|nullable|string|rg',
            'skin_color' => ['sometimes', 'nullable', 'string', Rule::in($skinColors)],
            'relative_relation_type' => 'sometimes|nullable|string|max:255',
            'relative_name' => 'sometimes|nullable|string|max:255',
            'relative_phone' => 'sometimes|nullable|string|phone',
            'addresses' => 'sometimes|nullable|array',
            'addresses.*.street' => 'required_with:addresses|string|max:255',
            'addresses.*.number' => 'required_with:addresses|string|max:255',
            'addresses.*.district' => 'sometimes|nullable|string|max:255',
            'addresses.*.complement' => 'sometimes|nullable|string|max:255',
            'addresses.*.state_id' => 'required_with:addresses|exists:states,id',
            'addresses.*.city_id' => 'required_with:addresses|exists:cities,id',
        ];

        return $rules;
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

        Validator::extend('cpf', function ($attribute, $value, $parameters, $validator) {
            return DocumentValidator::validateCpf($value);
        });

        Validator::extend('rg', function ($attribute, $value, $parameters, $validator) {
            return DocumentValidator::validateRg($value);
        });
    }
}
