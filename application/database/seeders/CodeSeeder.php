<?php

namespace Database\Seeders;

use App\Models\CodeTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $codes = File::json('database/seeders/data/codes.json');

        foreach ($codes as $table => $data) {
            $codeTable = CodeTable::create([
                'name' => $table,
                'user_definable' => $data['user_definable'] === 'Yes',
            ]);

            $mappedCodes = [];

            foreach ($data['codes'] as $code => $codeData) {
                $codeModel = $codeTable->codes()->create([
                    'code' => $code,
                    'name' => $codeData['description'],
                    'additional_data' => $codeData['additional'] ?? '',
                ]);

                if ($codeData['default']) {
                    $codeTable->default()->associate($code);
                    $codeTable->save();
                }
            }
        }
    }
}
