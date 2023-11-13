<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use MongoDB\Laravel\Connection;


class EnquiryRulesGenerator extends Command
{
    protected $signature = 'enquiry:generate-rules {--output= : The name of the file to save the rules}';
    protected $description = 'Generate validation rules based on a MongoDB collection';

    protected Connection $connection;


    public function handle()
    {
        $document = DB::connection('mongodb')->collection('Enquiries')->first();

        if (!$document) {
            $this->error("Couldn't fetch document. Ensure the collection has data.");
            return;
        }

        $laravelRules = $this->convertToLaravelRules($document);

        $formattedRules = '[' . PHP_EOL;
        foreach ($laravelRules as $key => $rule) {
            $formattedRules .= "\t'{$key}' => '{$rule}'," . PHP_EOL;
        }
        $formattedRules .= ']';

        if ($this->option('output')) {
            file_put_contents($this->option('output'), $formattedRules);
            $this->info("Rules have been saved to " . $this->option('output'));
        } else {
            $this->info($formattedRules);
        }
    }


    private function convertToLaravelRules(array $document)
    {
        $laravelRules = [];

        foreach ($document as $field => $value) {
            $type = gettype($value);

            switch ($type) {
                case 'integer':
                    $laravelRules[$field] = 'integer';
                    break;
                case 'double':
                    $laravelRules[$field] = 'numeric';
                    break;
                case 'string':
                    if (str_contains($field, 'email')) {
                        $laravelRules[$field] = 'email';
                    } else {
                        $laravelRules[$field] = 'string';
                    }
                    break;
                case 'array':
                    $laravelRules[$field] = 'array';
                    break;
                case 'boolean':
                    $laravelRules[$field] = 'boolean';
                    break;
                default:
                    $laravelRules[$field] = '';
            }
        }

        return $laravelRules;
    }
}
