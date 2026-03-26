<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            'Kinder' => ['St. Mary', 'St. Joseph'],
            'Grade 1' => ['St. Agnes', 'St. Bernadette'],
            'Grade 2' => ['St. Catherine', 'St. Claire'],
            'Grade 3' => ['St. Dominic', 'St. Francis'],
            'Grade 4' => ['St. Gabriel', 'St. Michael'],
            'Grade 5' => ['St. John', 'St. Luke'],
            'Grade 6' => ['St. Mark', 'St. Matthew'],
            'Grade 7' => ['Hope', 'Faith'],
            'Grade 8' => ['Faith', 'Charity'],
            'Grade 9' => ['Charity', 'Courage'],
            'Grade 10' => ['Courage', 'Wisdom'],
            'Grade 11' => ['STEM-A', 'ABM-A', 'HUMSS-A', 'GAS-A'],
            'Grade 12' => ['STEM-A', 'ABM-A', 'HUMSS-A', 'GAS-A'],
        ];

        foreach ($sections as $gradeLevel => $items) {
            foreach ($items as $sectionName) {
                Section::updateOrCreate(
                    [
                        'grade_level' => $gradeLevel,
                        'section_name' => $sectionName,
                    ],
                    [
                        'capacity' => 30,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
