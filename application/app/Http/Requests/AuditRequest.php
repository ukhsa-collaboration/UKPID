<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Rules\ValidUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AuditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('audit.read');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('owner')) {
            $this->merge([
                'owner' => $this->owner ? User::find($this->owner) : $this->owner,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            // Filter audits by the user who did the action.
            'owner' => ['sometimes', new ValidUser],
            // Filter audits by the field changed.
            'field' => ['sometimes', 'string'],
            // Get audits logged after a date.
            'date_from' => ['sometimes', 'date'],
            // Get audits logged before a date.
            'date_to' => ['sometimes', 'date'],
            // Filter audits by the event.
            'event' => ['sometimes', 'string', Rule::in(['created', 'updated', 'deleted', 'restored'])],
            // Set the order the audits are returned in.
            'order' => ['sometimes', Rule::in(['desc', 'asc'])],
            // Sort retrieved audits by the parameter.
            'order_by' => ['sometimes', Rule::in(['date', 'id'])],
        ];
    }
}
