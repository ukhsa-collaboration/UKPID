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
        // camel case
        $headers = array_map(function ($v) {
            return lcfirst(str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($v)))));
        }, $headers);

        $enquiries = [];
        $currentEnquiry = [];
        $enquiryNumber = 0;

        while (!feof($handle) && $enquiryNumber <= $maxEnquiries) {

            // https://gist.github.com/jbratu/29b3ba6133fd17285b9fff665fada315
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

    private static function splitValueIfNeeded($value)
    {
        $specialCharacters = ["ü", "Ã¼"];

        foreach ($specialCharacters as $specialChar) {
            if (str_contains($value, $specialChar)) {
                return explode($specialChar, $value);
            }
        }

        return $value;
    }


    private static function createEnquiry($headers, $row) : array
    {
        $enquiry = [];
        foreach ($row as $i => $v) {
            $trimmedValue = trim($v);
            $enquiry[$headers[$i]] = self::splitValueIfNeeded($trimmedValue);
        }
        return $enquiry;
    }

    private static function extendEnquiry($currentEnquiry, $headers, $row) : array
    {
        foreach ($row as $i => $v) {
            $trimmedValue = trim($v);
            if (empty($trimmedValue)) {
                continue;
            }

            $header = $headers[$i];
            $splitValues = self::splitValueIfNeeded($trimmedValue);

            // ensure $splitValues and $currentEnquiry[$header] are both arrays, then merge them
            if (!is_array($splitValues)) {
                $splitValues = [$splitValues];
            }

            if (empty($currentEnquiry[$header])) {
                $currentEnquiry[$header] = $splitValues;
            } else {
                if (!is_array($currentEnquiry[$header])) {
                    $currentEnquiry[$header] = [$currentEnquiry[$header]];
                }
                $currentEnquiry[$header] = array_merge($currentEnquiry[$header], $splitValues);
            }
        }

        return $currentEnquiry;
    }


}
