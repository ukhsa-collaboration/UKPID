<?php

class CsvReader
{
    public static function execute($csvFile, $maxEnquiries = 999999) : array
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

class CsvSplitter {
    const OUTPUT_DIR = "data/output";
    public static function execute($filePath, $enquiriesPerFile = 1000, $maxFileCount = 3) : void
    {
        $handle = self::openCsv($filePath);
        $headers = fgetcsv($handle);
        self::ensureOutputDirectoryExists();

        $fileCount = $enquiryCount = 0;
        $currentFile = null;

        while (!feof($handle)) {
            $row = fgetcsv($handle);

            if (self::isNewEnquiry($row, $headers)) {
                $enquiryCount++;

                if (self::shouldSwitchFile($currentFile, $enquiryCount, $enquiriesPerFile)) {
                    $fileCount++;
                    if ($fileCount > $maxFileCount) {
                        break;
                    }

                    $currentFile = self::switchFile($currentFile, $fileCount, $headers);
                    $enquiryCount = 1;
                }
            }

            if ($row) {
                fputcsv($currentFile, $row);
            }
        }

        self::closeFile($currentFile);
        fclose($handle);
    }

    // todo return type of resource?
    private static function openCsv($filePath)
    {
        $handle = fopen($filePath, 'r');
        if (!$handle)  {
            throw new \RuntimeException("Failed to open source file: {$filePath}");
        }

        return $handle;
    }

    private static function ensureOutputDirectoryExists() : void
    {
        if (!is_dir(self::OUTPUT_DIR)) {
            mkdir(self::OUTPUT_DIR, 0777, true);
        }
    }

    private static function isNewEnquiry($row, $headers) : bool
    {
        $keyIndex = array_search('Key', $headers);
        return !empty($row[$keyIndex]);
    }

    private static function shouldSwitchFile($currentFile, $enquiryCount, $enquiriesPerFile) : bool
    {
        return $currentFile === null || $enquiryCount > $enquiriesPerFile;
    }

    private static function switchFile($currentFile, $fileCount, $headers)
    {
        if (is_resource($currentFile)) {
            fclose($currentFile);
        }
        return self::createNewFile($fileCount, $headers);
    }

    private static function closeFile($currentFile) : void
    {
        if (is_resource($currentFile)) {
            fclose($currentFile);
        }
    }

    private static function createNewFile($fileCount, $headers)
    {
        $fileName = self::OUTPUT_DIR . "/split_{$fileCount}.csv";
        echo "Creating file $fileName\n";
        $file = fopen($fileName, 'w');

        if (!$file) {
            throw new \RuntimeException("Failed to create new file: {$fileName}");
        }

        fputcsv($file, $headers);
        return $file;
    }
}



$csv = "data/Enquiry Table 1.csv";
//$test = CsvReader::execute($csv, 1);
// don't use json_encode, too confusing to tell where encoding issues lie
//echo var_dump($test);

//$csv = "data/Test Export Edited.csv";
CsvSplitter::execute($csv, 1, 5);