<?php

class CsvReader
{

    public static function readCsv($csvFile, $maxEnquiries = 999999) : array
    {
        $handle = fopen($csvFile, 'r');
        $headers = fgetcsv($handle);
        $headers = array_filter($headers);
        $headers = mb_convert_encoding($headers, 'UTF-8', 'ISO-8859-1');

        $enquiries = [];
        $currentEnquiry = [];
        $enquiryNumber = 0;

        while (!feof($handle) && $enquiryNumber <= $maxEnquiries) {

            $row = fgetcsv($handle);
            $row = mb_convert_encoding($row, 'UTF-8', 'ISO-8859-1');
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



$csv = "data/Enquiry Table 1.csv";
$test = CsvReader::readCSV($csv, 1);
// don't use json_encode, too confusing to tell where encoding issues lie
echo var_dump($test);
