<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCodeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 'string', 'max:255',
                // Ensure the code name is unique for the related code_table
                Rule::unique('codes', 'name')->where('code_table_id', $this->input('code_table_id')),
            ],
            'code_table_id' => ['required', 'exists:App\Models\CodeTable,id'],
            'additional_data' => ['sometimes', 'string'],
        ];
    }
}
