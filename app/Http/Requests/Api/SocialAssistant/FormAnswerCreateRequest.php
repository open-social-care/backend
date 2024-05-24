<?php

namespace App\Http\Requests\Api\SocialAssistant;

use App\Models\FormAnswer;
use App\Models\ShortQuestion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class FormAnswerCreateRequest extends FormRequest
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
        $subject = $this->route('subject');

        return auth()->user()->can('createBySubject', [FormAnswer::class, $subject]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'form_template_id' => 'required|integer|exists:form_templates,id',
            'short_answers' => 'required|array',
            'short_answers.*.short_question_id' => 'required|integer|exists:short_questions,id',
            'short_answers.*.answer' => 'sometimes|max:255|required_answer',
        ];

        return $rules;
    }

    private function registerCustomValidations(): void
    {
        Validator::extend('required_answer', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('/short_answers\.(\d+)\.answer/', $attribute, $matches)) {
                $index = $matches[1];
                $data = $validator->getData();
                $shortQuestionId = $data['short_answers'][$index]['short_question_id'];

                $shortQuestion = ShortQuestion::find($shortQuestionId);
                if ($shortQuestion && $shortQuestion->answer_required && ! $value) {
                    return false;
                }
            }

            return true;
        }, 'A resposta é obrigatória para esta pergunta.');
    }
}
