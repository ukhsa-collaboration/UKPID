<?php

namespace App\Http\Requests;

class UpdateEnquiryRequest extends StoreEnquiryRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $enquiry = $this->route('enquiry');
        $version = $enquiry->version;

        return $this->generateRulesFromFormDefinition($version, true);
    }
}
