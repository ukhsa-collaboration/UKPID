<?php

namespace App\Migration;

use App\Models\Enquiry;

class DbWriter
{
    public function write(array $data) : void
    {
        try {

            $timestamp = now()->toDateTimeString();
            $dataWithTimestamps = array_map(function($enquiryData) use ($timestamp) {
                return array_merge(
                    $enquiryData,
                    ['created_at' => $timestamp, 'updated_at' => $timestamp]
                );
            }, $data);

            Enquiry::insert($dataWithTimestamps);

        } catch (\Exception $e) {
            echo "Error writing to database: " . $e->getMessage();
        }
    }
}
