<?php

namespace App\Http\Requests\Todo;

use App\Http\Requests\ApiFormRequest;

class UpdateTodoRequest extends ApiFormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:100',
            'body' => 'sometimes|required|string|max:300',
            'completed' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The todo title is required',
            'title.max' => 'The todo title must not exceed 100 characters',
            'body.required' => 'The todo body is required',
            'body.max' => 'The todo body must not exceed 300 characters',
        ];
    }
}
