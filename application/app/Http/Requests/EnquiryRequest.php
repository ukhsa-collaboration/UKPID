<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public static function rules(): array
    {

        $arrayFields = ['enquirerAddress'];

        $arrayRules = [];

        foreach ($arrayFields as $field) {
            $arrayRules[$field] = [
                'bail',
                'sometimes',
                'nullable',
                function ($attribute, $value, $fail) use ($field) {
                    // Check if the value is an array and if every element matches the expected type
                    if (is_array($value)) {
                        foreach ($value as $item) {
                            if (!$item instanceof ExpectedType) { // Replace ExpectedType with the expected type, e.g., string or integer
                                $fail("The $attribute must be a single primitive or an array of primitives.");
                            }
                        }
                        // Check if the single value matches the expected type
                    } elseif (!$value instanceof ExpectedType) {
                        $fail("The $attribute must be a single primitive or an array of primitives.");
                    }
                },
            ];
        }

        // for help generating these, see EnquiryRulesGenerator
        $otherRules = [
            '_id' => '',
            'key' => 'string',
            'enquiryNumber' => 'string',
            'centreCode' => 'string',
            'enquiryDate' => 'string',
            'responder' => 'string',
            'enquiryNature' => 'string',
            'enquiryMechanism' => 'string',
            'sourceOfEnquiry' => 'string',
            'enquirerAddress' => 'array',
            'ward' => 'string',
            'enquirerEmail' => 'string',
            'enquirerPostcode' => 'string',
            'contactName' => 'string',
            'enquirerTelephone' => 'string',
            'extension' => 'string',
            'centreCallTakenFor' => 'string',
            'enquirer' => 'string',
            'investigationsPriorToCall' => 'string',
            'investigations' => 'string',
            'resultsOfInvestigations' => 'string',
            'treatmentsPriorToCall' => 'string',
            'treatments' => 'string',
            'priorFeatures' => 'string',
            'featuresDescription' => 'string',
            'featureCodes' => 'string',
            'gender' => 'string',
            'patientName' => 'string',
            'ageInYears' => 'string',
            'dob' => 'string',
            'lactating' => 'string',
            'pregnant' => 'string',
            'patientNumber' => 'string',
            'patientPostcode' => 'string',
            'weight' => 'string',
            'agentName' => 'string',
            'agentAmount' => 'string',
            'agentUnit' => 'string',
            'durationOfExposure' => 'string',
            'durationUnits' => 'string',
            'timeSinceExposure' => 'array',
            'timeSinceExposureUnits' => 'array',
            'exposureType' => 'array',
            'exposureRoute' => 'array',
            'incidentLocation' => 'string',
            'incidentPostcode' => 'string',
            'treatmentsRecommended' => 'string',
            'investigationsRecommended' => 'string',
            'referral' => 'string',
            'poisoningSeverityScore' => 'string',
            'sourceOfInformation' => 'string',
            'followupRequired' => 'string',
            'enquiryOutcome' => 'string',
            'circumstances' => 'string',
            'medicalHistory' => 'string',
            'comments' => 'string',
            'completionDate' => 'string',
            'completionTime' => 'string',
            'count' => 'string',
            'created_at' => 'string',
            'updated_at' => 'string',
        ];

        return array_merge($arrayRules, $otherRules);
    }
}
