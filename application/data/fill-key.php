<?php


$filePath = 'test_with_headers.csv';
$file = fopen($filePath, 'r');
$tempFile = fopen('test_with_key.csv', 'w');

if (!$file || !$tempFile) {
    die("Unable to open the file.");
}

$headers = fgetcsv($file);
fputcsv($tempFile, $headers);

$enquiryNumber = 0;

// Get the index positions of the necessary columns
$centreCodeIndex = array_search('CENTRE_CODE', $headers);
$keyIndex = array_search('Key', $headers);
$enquiryNumberIndex = array_search('ENQUIRY_NUMBER', $headers);

while (($row = fgetcsv($file)) !== FALSE) {
    if (!empty(trim($row[$centreCodeIndex]))) {
        $enquiryNumber++;
        $row[$keyIndex] = "{$enquiryNumber}*{$row[$centreCodeIndex]}";
        $row[$enquiryNumberIndex] = $enquiryNumber;
    }

    fputcsv($tempFile, $row);
    echo "\rProcessed enquiry: {$enquiryNumber}";
}

fclose($file);
fclose($tempFile);

rename('temp.csv', $filePath);