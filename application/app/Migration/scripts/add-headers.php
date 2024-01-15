<?php

function get_headers_from_file($file_path) {
    $file_handle = fopen($file_path, 'r');
    $headers = fgetcsv($file_handle);
    fclose($file_handle);
    return $headers;
}

function merge_headers_with_order($source_headers, $target_headers) {
    $headers_to_add = array_diff($source_headers, $target_headers);
    foreach ($headers_to_add as $header) {
        $position = array_search($header, $source_headers);
        array_splice($target_headers, $position, 0, $header);
    }
    return $target_headers;
}

function process_rows_and_write_to_file($source_file_handle, $new_file_handle, $final_headers, $existing_headers) {
    $row_count = 0;
    while ($row = fgetcsv($source_file_handle)) {
        $new_row = array_fill(0, count($final_headers), ''); // prepare a row with empty values
        foreach ($existing_headers as $index => $header) {
            if (($key = array_search($header, $final_headers)) !== false) {
                $new_row[$key] = $row[$index]; // set the value at the correct position
            }
        }
        fputcsv($new_file_handle, $new_row);
        $row_count++;
        echo "\rProcessed row: {$row_count}";
    }
}

// Usage
$first_file_path = 'Enquiry Table 2.csv';
$second_file_path = 'Test Export Edited.csv';
$new_file_path = 'test_with_headers.csv';

// get headers from both CSV files
$source_headers = get_headers_from_file($first_file_path);
$target_headers = get_headers_from_file($second_file_path);
// merge headers while maintaining the order
$final_headers = merge_headers_with_order($source_headers, $target_headers);

$source_file_handle = fopen($second_file_path, 'r');
$new_file_handle = fopen($new_file_path, 'w');

fputcsv($new_file_handle, $final_headers);
fgetcsv($source_file_handle);
process_rows_and_write_to_file($source_file_handle, $new_file_handle, $final_headers, $target_headers);

fclose($source_file_handle);
fclose($new_file_handle);

echo "\nFinished processing. The modified file has been saved to '{$new_file_path}'.\n";
