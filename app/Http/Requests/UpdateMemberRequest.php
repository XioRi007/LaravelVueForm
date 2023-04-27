<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;


class UpdateMemberRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Prepare inputs for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if($this->has('hidden')){
            $this->merge([
                'hidden' => filter_var($this->hidden, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstName'=>'string|max:30',
            'lastName'=>'string|max:30',
            'birthdate'=>'date_format:Y-m-d',
            'reportSubject'=>'string|max:255',
            'country'=>'string|max:50',
            'phone'=>'string|max:16',
            'email'=>[
                'email',
                'max:50',
                Rule::unique('members')->ignore($this->route('id')),
            ],
            'hidden'=>'boolean',
            'photo'=>'file',
            'about'=>'string',
            'company'=>'string|max:50',
            'position'=>'string|max:50',
        ];
    }
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422)
        );
    }
}
