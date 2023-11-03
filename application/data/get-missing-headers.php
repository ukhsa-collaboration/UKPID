<?php

function get_headers_from_file($file_path) {
    $file_handle = fopen($file_path, 'r');
    $headers = fgetcsv($file_handle);
    fclose($file_handle);
    return $headers;
}

$first_file_path = 'Enquiry Table 2.csv';
$second_file_path = 'Test Export Edited.csv';

// get headers from both CSV files
$first_headers = get_headers_from_file($first_file_path);
$second_headers = get_headers_from_file($second_file_path);

// calculate headers that are in the first file but not in the second
$missing_headers = array_diff($first_headers, $second_headers);
echo json_encode(array_values($missing_headers), JSON_PRETTY_PRINT);
