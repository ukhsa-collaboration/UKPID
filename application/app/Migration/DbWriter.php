<?php

namespace App\Migration;

use App\Http\Requests\EnquiryRequest;
use App\Models\Enquiry;
use Illuminate\Support\Facades\Validator;

class DbWriter
{
    public function write(array $data) : void
    {
        $timestamp = now()->toDateTimeString();
        $dataWithTimestamps = array_map(function($enquiryData) use ($timestamp) {

            /** todo rethink this
            $validator = Validator::make($enquiryData, EnquiryRequest::rules());
            if ($validator->fails()) {
                // Handle the failed validation here, for example, throw an exception or log the errors.
                throw new \Exception('Validation failed: ' . json_encode($validator->errors()));
            }*/

            return array_merge(
                $enquiryData,
                ['created_at' => $timestamp, 'updated_at' => $timestamp]
            );
        }, $data);

        Enquiry::insert($dataWithTimestamps);
    }
}
