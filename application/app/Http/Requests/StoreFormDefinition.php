<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFormDefinition extends FormRequest
{
    public function rules()
    {
        return [];
    }

    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateRequiredDesktopVersion($validator);
            $this->validateFormDefinition($validator);
        });
    }

    private function validateRequiredDesktopVersion($validator)
    {
        $requiredDesktopVersion = $this->query('requiredDesktopVersion');
        if ($requiredDesktopVersion !== null && ! preg_match('/^[\d.]+$/', $requiredDesktopVersion)) {
            $validator->errors()->add('requiredDesktopVersion', 'The required desktop version format is invalid.');
        }
    }

    private function validateFormDefinition($validator)
    {
        $formDefinition = $this->json()->all();
        foreach ($formDefinition as $tabName => $columns) {
            $tabPath = $tabName;
            if (! $this->validateTab($columns, $tabPath, $validator)) {
                continue;
            }

            foreach ($columns as $columnIndex => $column) {
                $columnPath = "{$tabPath}.column[{$columnIndex}]";
                if (! $this->validateColumn($column, $columnPath, $validator)) {
                    continue;
                }

                foreach ($column['sections'] as $sectionIndex => $section) {
                    $sectionName = $section['title'] ?? "section[{$sectionIndex}]";
                    $sectionPath = "{$columnPath}.{$sectionName}";
                    if (! $this->validateSection($section, $sectionPath, $validator)) {
                        continue;
                    }

                    foreach ($section['fields'] as $fieldIndex => $field) {

                        // use field name if available for a more descriptive path
                        $fieldName = $field['name'] ?? "field[{$fieldIndex}]";
                        $fieldPath = "{$sectionPath}.{$fieldName}";

                        if ($field['type'] === 'form') {
                            $this->validateFormField($field, $fieldPath, $validator);
                        } elseif ($field['type'] === 'fieldset') {
                            $this->validateFieldset($field, $fieldPath, $validator);
                        } elseif ($field['type'] === 'space') {
                            if (isset($field['width']) && ! is_numeric($field['width'])) {
                                $validator->errors()->add("{$fieldPath}.width", 'Field width must be numeric.');
                            }
                        } else {
                            $this->validateField($field, $fieldPath, $validator);
                        }
                    }
                }
            }
        }
    }

    private function validateTab($columns, $tabName, $validator)
    {
        if (! is_array($columns)) {
            $validator->errors()->add($tabName, 'The tab content should be an array.');

            return false;
        }

        return true;
    }

    private function validateColumn($column, $path, $validator)
    {
        $isValid = true;
        if (isset($column['width']) && ! is_numeric($column['width'])) {
            $validator->errors()->add("{$path}.width", 'Width must be numeric.');
            $isValid = false;
        }

        if (! isset($column['sections']) || ! is_array($column['sections'])) {
            $validator->errors()->add("{$path}.sections", 'Each column must contain a sections array.');
            $isValid = false;
        }

        return $isValid;
    }

    private function validateSection($section, $path, $validator)
    {
        $isValid = true;
        if (! isset($section['title'])) {
            $validator->errors()->add("{$path}", 'Each section must contain a title.');
            $isValid = false;
        }

        if (! isset($section['fields']) || ! is_array($section['fields'])) {
            $validator->errors()->add("{$path}", 'Each section must contain a fields array.');
            $isValid = false;
        }

        return $isValid;
    }

    private function validateField($field, $path, $validator)
    {
        $requiredProperties = ['name', 'field'];

        foreach ($requiredProperties as $requiredProperty) {
            if (! isset($field[$requiredProperty])) {
                $validator->errors()->add("{$path}.{$requiredProperty}", "The {$requiredProperty} is required and must not be empty.");

                return;
            }
        }

        if (isset($field['width']) && ! is_numeric($field['width'])) {
            $validator->errors()->add("{$path}.width", 'Field width must be numeric.');
        }
    }

    private function validateFormField($field, $path, $validator)
    {
        $requiredProperties = ['cells', 'defaultRows'];

        foreach ($requiredProperties as $requiredProperty) {
            if (! isset($field[$requiredProperty])) {
                $validator->errors()->add("{$path}.{$requiredProperty}", "The {$requiredProperty} is required and must not be empty.");

                return;
            }
        }

        if (isset($field['width']) && ! is_numeric($field['width'])) {
            $validator->errors()->add("{$path}.width", 'Field width must be numeric.');
        }

        foreach ($field['cells'] as $cellIndex => $cell) {

            $fieldName = $field['name'] ?? "cell[{$cellIndex}]";
            $this->validateField($cell, $fieldName, $validator);
        }
    }

    private function validateFieldset($field, $path, $validator)
    {
        $requiredProperties = ['fieldsetLegend', 'fields'];

        foreach ($requiredProperties as $requiredProperty) {
            // allow NULL in this case
            if (! array_key_exists($requiredProperty, $field)) {
                $validator->errors()->add("{$path}.{$requiredProperty}", "The {$requiredProperty} is required.");

                return;
            }
        }

        if (isset($field['width']) && ! is_numeric($field['width'])) {
            $validator->errors()->add("{$path}.width", 'Field width must be numeric.');
        }

        foreach ($field['fields'] as $cellIndex => $cell) {
            $fieldName = $field['name'] ?? "field[{$cellIndex}]";
            $this->validateField($cell, $fieldName, $validator);
        }
    }
}
