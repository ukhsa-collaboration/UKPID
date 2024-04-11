<?php

namespace App\Http\Requests;

use App\Models\FormDefinition;
use Illuminate\Foundation\Http\FormRequest;

class StoreEnquiryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $version = $this->request->get('version');
        $enquiryRules = $this->generateRulesFromFormDefinition($version);

        return array_merge(
            ['key' => 'string|required|unique:mongodb.Enquiries', 'version' => 'numeric|required'],
            $enquiryRules
        );
    }

    protected function withValidator($validator)
    {
        $this->preventExtraFields($validator);
        $this->processConditionals($validator);
    }

    protected function preventExtraFields($validator)
    {
        $validator->after(function ($validator) {
            $extraFields = array_diff(array_keys($this->all()), array_keys($this->rules()));
            foreach ($extraFields as $field) {
                $validator->errors()->add($field, 'This field is not allowed.');
            }
        });
    }

    protected function processConditionals($validator)
    {
        // process the conditionals generated in generateLaravelValidationRules
        $conditionals = $this->rules()['_conditionals'] ?? [];
        foreach ($conditionals as $field => $conditional) {
            $conditional['value'] = (array) $conditional['value'];

            if ($conditional['compare'] === 'includes') {
                $validator->sometimes($field, 'prohibited', function ($input) use ($conditional) {
                    return $this->processIncludes($input, $conditional);
                });
            } else {
                // default comparison method is equals
                $validator->sometimes($field, 'prohibited', function ($input) use ($conditional) {
                    return $this->processEquals($input, $conditional);
                });
            }
        }
    }

    private function processIncludes($input, array $conditional): bool
    {
        $inputValue = strtolower($input[$conditional['field']] ?? '');
        foreach ($conditional['value'] as $value) {
            if (str_contains($inputValue, strtolower($value))) {
                return false;
            }
        }

        return true;
    }

    private function processEquals($input, array $conditional): bool
    {
        $field = strtolower($input[$conditional['field']] ?? '');

        return ! in_array($field, array_map('strtolower', $conditional['value']));
    }

    protected function generateRulesFromFormDefinition(string $version, bool $ignoreRequired = false): array
    {
        $formDefinition = FormDefinition::findOrFail($version);
        $fields = $this->extractFields($formDefinition->toArray());

        return $this->generateLaravelValidationRules($fields, $ignoreRequired);
    }

    private function extractFields(array $formDefinition): array
    {
        $fields = [];

        foreach ($formDefinition as $k => $fieldDefinitions) {
            if ($k === 'fields' || $k === 'cells') {

                foreach ($fieldDefinitions as $j => $fieldDefinition) {

                    if ($fieldDefinition['type'] === 'space') {
                        unset($fieldDefinitions[$j]);
                    }

                    if (isset($fieldDefinition['type']) && ($fieldDefinition['type'] === 'fieldset')) {
                        $fields = array_merge($fields, $fieldDefinition['fields']);
                        unset($fieldDefinitions[$j]);
                    }

                    if (isset($fieldDefinition['type']) && ($fieldDefinition['type'] === 'form')) {
                        $fields = array_merge($fields, $fieldDefinition['cells']);
                        unset($fieldDefinitions[$j]);
                    }
                }

                $fields = array_merge($fields, $fieldDefinitions);

            } elseif (is_array($fieldDefinitions)) {
                $fields = array_merge($fields, $this->extractFields($fieldDefinitions));
            }
        }

        return $fields;
    }

    private function generateLaravelValidationRules(array $fields, bool $ignoreRequired = false): array
    {
        $rules = [];

        foreach ($fields as $field) {
            $fieldRules = [];

            $fieldType = $field['type'] ?? 'string';

            // required fields only when creating, not updating
            if (! $ignoreRequired && isset($field['required']) && $field['required']) {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            switch ($fieldType) {
                case 'text':
                case 'string':
                    $fieldRules[] = 'string';
                    break;
                case 'date':
                    $fieldRules[] = 'date';
                    break;
                case 'time':
                    $fieldRules[] = 'date_format:H:i:s';
                    break;
                case 'checkbox':
                case 'dropdown':
                    if (isset($field['values'])) {
                        $fieldRules[] = 'in:'.implode(',', $field['values']);
                    }
                    break;
            }

            // store the conditionals to be processed in withValidator
            if (isset($field['conditional'])) {
                $rules['_conditionals'][$field['field']] = $field['conditional'];
            }

            $rules[$field['field']] = implode('|', $fieldRules);
        }

        return $rules;
    }
}
