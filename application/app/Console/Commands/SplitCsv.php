<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Migration\CsvSplitter;
class SplitCsv extends Command
{
    protected $signature = 'enquiries:split-csv
                        {inputFile : The path to the input CSV file}
                        {outputDir=output : The directory where split files will be saved}
                        {enquiriesPerFile=10 : The number of enquiries per split file}
                        {maxFileCount=10 : The maximum number of split files to generate}';
    protected $description = 'Split a large CSV file into smaller chunks';
    public function handle() {
        $inputFile = $this->argument('inputFile');
        $outputDir = $this->argument('outputDir');
        $enquiriesPerFile = $this->argument('enquiriesPerFile');
        $maxFileCount = $this->argument('maxFileCount');

        CsvSplitter::execute($inputFile, $outputDir, $enquiriesPerFile, $maxFileCount);

        $this->info("CSV {$inputFile} successfully split, output in directory: {$outputDir}");
    }
}
