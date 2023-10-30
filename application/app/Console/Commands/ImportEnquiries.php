<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Migration\CsvReader;
use App\Migration\DbWriter;

class ImportEnquiries extends Command
{
    private DbWriter $dbWriter;
    protected $signature = 'enquiries:import {outputDir=output : The directory containing the split CSV files}';
    protected $description = 'Import split CSV files into MongoDB';

    public function __construct()
    {
        $this->dbWriter = new DbWriter();
        parent::__construct();
    }

    public function handle()
    {
        $outputDir = $this->argument('outputDir');

        if (!is_dir($outputDir)) {
            $this->error("The provided directory {$outputDir} does not exist!");
            return;
        }

        $splitFiles = array_diff(scandir($outputDir), ['..', '.']);

        if (empty($splitFiles)) {
            $this->info("No CSV files found in the $outputDir");
            return;
        }

        foreach ($splitFiles as $file) {
            $data = CsvReader::execute("{$outputDir}/{$file}");
            $this->info("Importing $file");
            $this->dbWriter->write($data);
        }

        $this->info("All CSV files in {$outputDir} have been successfully imported");
    }
}
