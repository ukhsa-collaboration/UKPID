<?php

namespace Database\Seeders;

use App\Models\SourceOfEnquiry;
use Illuminate\Database\Seeder;

class SourceOfEnquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            'Ambulance',
            'Community Pharmacist',
            'Dental Establishment',
            'Fire',
            'General Practitioner/Primary Care',
            'HM Forces',
            'HM Prison',
            'Hospital',
            'Member of Public',
            'NHS 111',
            'NHS Direct/NHS 24',
            'Nursing/Care Home',
            'Other',
            'Police',
            'School',
            'Support Services (Carers, Samaritans, Childline, Age Concern)',
            'Unknown',
            'Urgent Care Centre',
            'Veterinary Practice',
            'Walk in Centre',
        ];

        foreach ($entries as $entry) {
            SourceOfEnquiry::create([
                'name' => $entry,
            ]);
        }
    }
}
