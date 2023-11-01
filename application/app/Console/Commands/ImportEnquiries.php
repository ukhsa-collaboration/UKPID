<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Migration\CsvReader;
use App\Migration\DbWriter;

class ImportEnquiries extends Command
{
    private DbWriter $dbWriter;
    protected $signature = 'enquiries:import {inputDir=input : The directory containing the split CSV files}';
    protected $description = 'Import split CSV files into MongoDB';

    public function __construct()
    {
        $this->dbWriter = new DbWriter();
        parent::__construct();
    }

    public function handle()
    {
        $inputDir = $this->argument('inputDir');

        if (!is_dir($inputDir)) {
            $this->error("The provided directory {$inputDir} does not exist!");
            return;
        }

        $splitFiles = array_diff(scandir($inputDir), ['..', '.']);

        if (empty($splitFiles)) {
            $this->info("No CSV files found in the $inputDir");
            return;
        }

        foreach ($splitFiles as $file) {
            $data = CsvReader::execute("{$inputDir}/{$file}");
            $this->info("Importing $file");
            $this->dbWriter->write($data);
        }

        $this->info("All CSV files in {$inputDir} have been successfully imported");
    }
}
