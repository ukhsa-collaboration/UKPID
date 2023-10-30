<?php

namespace App\Migration;

use App\Models\Enquiry;

class DbWriter {

    public function write(array $data) {
        try {
            foreach ($data as $enquiryData) {
                $enquiry = new Enquiry;
                $enquiry->fill($enquiryData);
                $enquiry->save();
            }
        } catch (Exception $e) {
            echo "Error writing to database: " . $e->getMessage();
        }
    }
}
