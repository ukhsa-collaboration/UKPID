<?php

namespace Database\Seeders;

use App\Models\FormDefinition;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class FormDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $jsonPath = database_path('seeders/data/form_definition.json');

        if (File::exists($jsonPath)) {
            $jsonData = File::json($jsonPath);

            FormDefinition::firstOrCreate(
                ['version' => '1'],
                ['requiredDesktopVersion' => '1.0.0', 'definition' => $jsonData]
            );

        } else {
            echo 'The JSON file does not exist.';
        }
    }
}
