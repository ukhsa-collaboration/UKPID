<?php

namespace App\Migration;

class CsvSplitter
{
    public static function execute($inputFile, $outputDir, $enquiriesPerFile = 10, $maxFileCount = 10) : void
    {
        $handle = self::openCsv($inputFile);
        $headers = fgetcsv($handle);
        $headers = array_filter($headers);

        self::ensureOutputDirectoryExists($outputDir);

        $fileCount = $enquiryCount = 0;
        $currentFile = null;

        while (!feof($handle)) {
            $row = fgetcsv($handle);

            if (self::isNewEnquiry($row, $headers)) {
                $enquiryCount++;

                if (self::shouldSwitchFile($currentFile, $enquiryCount, $enquiriesPerFile)) {
                    $fileCount++;
                    // be careful if you don't want a huge amount of files
                    if ($fileCount > $maxFileCount) {
                        break;
                    }

                    $currentFile = self::switchFile($currentFile, $outputDir, $fileCount, $headers);
                }
            }

            if ($row) {
                fputcsv($currentFile, $row);
            }
        }

        self::closeFile($currentFile);
        self::closeFile($handle);
    }

    private static function openCsv($inputFile)
    {
        $handle = fopen($inputFile, 'r');
        if (!$handle)  {
            throw new \RuntimeException("Failed to open source file: {$inputFile}");
        }

        return $handle;
    }

    private static function ensureOutputDirectoryExists(string $outputDir) : void
    {
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0755, true);
        }
    }

    private static function isNewEnquiry($row, $headers) : bool
    {
        $keyIndex = array_search('Key', $headers);
        return !empty($row[$keyIndex]);
    }

    private static function shouldSwitchFile($currentFile, $enquiryCount, $enquiriesPerFile) : bool
    {
        return $currentFile === null || $enquiryCount % $enquiriesPerFile == 0 && $enquiryCount != 0;
    }

    private static function switchFile($currentFile, $outputDir, $fileCount, $headers)
    {
        self::closeFile($currentFile);
        return self::createNewFile($outputDir, $fileCount, $headers);
    }

    private static function closeFile($currentFile) : void
    {
        if (is_resource($currentFile)) {
            fclose($currentFile);
        }
    }

    private static function createNewFile($outputDir, $fileCount, $headers)
    {
        $fileName = $outputDir . "/split_{$fileCount}.csv";
        echo "Creating file $fileName\n";
        $file = fopen($fileName, 'w');

        if (!$file) {
            throw new \RuntimeException("Failed to create new file: {$fileName}");
        }

        fputcsv($file, $headers);
        return $file;
    }
}
