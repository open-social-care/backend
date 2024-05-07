<?php

namespace App\Http\Requests\Api\Manager;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class FormTemplateCreateRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'has_file_uploads' => 'required|boolean',
            'short_questions' => 'nullable|array',
            'short_questions.*.description' => 'required_with:short_questions|string|max:255',
            'short_questions.*.answer_required' => 'required_with:short_questions|boolean',
            'multiple_choice_questions' => 'nullable|array',
            'multiple_choice_questions.*.description' => 'required_with:multiple_choice_questions|string|max:255',
            'multiple_choice_questions.*.answer_required' => 'required_with:multiple_choice_questions|boolean',
            'multiple_choice_questions.*.multiple_choice_options' => 'required_with:multiple_choice_questions|array',
            'multiple_choice_questions.*.multiple_choice_options.*.description' => 'required_with:multiple_choice_questions|string|max:255',
        ];

        return $rules;
    }
}
