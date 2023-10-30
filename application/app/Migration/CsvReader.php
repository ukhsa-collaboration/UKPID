<?php

namespace App\Migration;

class CsvReader
{
    public static function execute($csvFile, $maxEnquiries = 999999) : array
    {
        $handle = fopen($csvFile, 'r');
        $headers = fgetcsv($handle);
        $headers = array_filter($headers);
        $headers = mb_convert_encoding($headers, 'UTF-8', 'ISO-8859-1');
        // strip all control characters and extended ASCII characters e.g. BOM
        $headers[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $headers[0]);

        $enquiries = [];
        $currentEnquiry = [];
        $enquiryNumber = 0;

        while (!feof($handle) && $enquiryNumber <= $maxEnquiries) {

            $row = fgetcsv($handle);
            $row = mb_convert_encoding($row, 'UTF-8', 'ISO-8859-1');

            if (!is_array($row)) {
                // probably whitespace at EOF, just end
                break;
            }

            $row = array_slice($row, 0, count($headers));

            $keyIndex = array_search('Key', $headers);
            // if we encounter a value in the Key column, it's a new enquiry
            if (!empty($row[$keyIndex])) {
                // add current enquiry before creating new one
                if ($currentEnquiry) {
                    $enquiries[] = $currentEnquiry;
                }

                $currentEnquiry = self::createEnquiry($headers, $row);
                $enquiryNumber++;
            } else {
                $currentEnquiry = self::extendEnquiry($currentEnquiry, $headers, $row);
            }
        }

        // add final enquiry
        $enquiries[] = $currentEnquiry;

        fclose($handle);
        return $enquiries;
    }

    private static function createEnquiry($headers, $row) : array
    {
        $enquiry = [];
        foreach ($row as $i => $v) {
            $enquiry[$headers[$i]] = trim($v);
        }
        return $enquiry;
    }

    private static function extendEnquiry($currentEnquiry, $headers, $row) : array
    {
        foreach ($row as $i => $v) {
            $v = trim($v);
            if (empty($v)) {
                continue;
            }

            $header = $headers[$i];
            if (empty($currentEnquiry[$header])) {
                $currentEnquiry[$header] = $v;
            } else {
                if (!is_array($currentEnquiry[$header])) {
                    $currentEnquiry[$header] = [$currentEnquiry[$header]];
                }
                $currentEnquiry[$header][] = $v;
            }
        }
        return $currentEnquiry;
    }
}
