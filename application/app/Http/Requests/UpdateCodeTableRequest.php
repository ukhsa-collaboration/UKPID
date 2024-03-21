<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCodeTableRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255', 'unique:code_tables,name'],
            'default_id' => [
                'sometimes',
                'nullable',
                // Ensure default code belongs to the code table
                Rule::exists('codes', 'id')->where(function (Builder $query) {
                    return $query->where('code_table_id', $this->code_table->id);
                }),
            ],
        ];
    }
}
