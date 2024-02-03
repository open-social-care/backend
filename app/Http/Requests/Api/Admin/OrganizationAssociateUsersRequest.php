<?php

namespace App\Http\Requests\Api\Admin;

use App\Enums\DocumentTypesEnum;
use App\Support\DocumentValidator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrganizationAssociateUsersRequest extends FormRequest
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
        return [
            'users' => 'required|array',
            'users.*' => 'required|exists:users,id',
        ];
    }
}
