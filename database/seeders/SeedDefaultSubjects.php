<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SeedDefaultSubjects extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['subject_code' => 'K-ENG', 'subject_name' => 'Kinder English', 'grade_level' => 'Kinder', 'units' => 1],
            ['subject_code' => 'K-MAT', 'subject_name' => 'Kinder Mathematics', 'grade_level' => 'Kinder', 'units' => 1],
            ['subject_code' => 'K-SCI', 'subject_name' => 'Kinder Science and Discovery', 'grade_level' => 'Kinder', 'units' => 1],
            ['subject_code' => 'K-READ', 'subject_name' => 'Reading Readiness', 'grade_level' => 'Kinder', 'units' => 1],
            ['subject_code' => 'K-MAPEH', 'subject_name' => 'Kinder MAPEH', 'grade_level' => 'Kinder', 'units' => 1],
            ['subject_code' => 'K-ESP', 'subject_name' => 'Values Education', 'grade_level' => 'Kinder', 'units' => 1],

            ['subject_code' => 'G1-ENG', 'subject_name' => 'English 1', 'grade_level' => 'Grade 1', 'units' => 1],
            ['subject_code' => 'G1-FIL', 'subject_name' => 'Filipino 1', 'grade_level' => 'Grade 1', 'units' => 1],
            ['subject_code' => 'G1-MAT', 'subject_name' => 'Mathematics 1', 'grade_level' => 'Grade 1', 'units' => 1],
            ['subject_code' => 'G1-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 1', 'grade_level' => 'Grade 1', 'units' => 1],
            ['subject_code' => 'G1-MAPEH', 'subject_name' => 'MAPEH 1', 'grade_level' => 'Grade 1', 'units' => 1],
            ['subject_code' => 'G1-AP', 'subject_name' => 'Araling Panlipunan 1', 'grade_level' => 'Grade 1', 'units' => 1],

            ['subject_code' => 'G2-ENG', 'subject_name' => 'English 2', 'grade_level' => 'Grade 2', 'units' => 1],
            ['subject_code' => 'G2-FIL', 'subject_name' => 'Filipino 2', 'grade_level' => 'Grade 2', 'units' => 1],
            ['subject_code' => 'G2-MAT', 'subject_name' => 'Mathematics 2', 'grade_level' => 'Grade 2', 'units' => 1],
            ['subject_code' => 'G2-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 2', 'grade_level' => 'Grade 2', 'units' => 1],
            ['subject_code' => 'G2-MAPEH', 'subject_name' => 'MAPEH 2', 'grade_level' => 'Grade 2', 'units' => 1],
            ['subject_code' => 'G2-AP', 'subject_name' => 'Araling Panlipunan 2', 'grade_level' => 'Grade 2', 'units' => 1],

            ['subject_code' => 'G3-ENG', 'subject_name' => 'English 3', 'grade_level' => 'Grade 3', 'units' => 1],
            ['subject_code' => 'G3-FIL', 'subject_name' => 'Filipino 3', 'grade_level' => 'Grade 3', 'units' => 1],
            ['subject_code' => 'G3-MAT', 'subject_name' => 'Mathematics 3', 'grade_level' => 'Grade 3', 'units' => 1],
            ['subject_code' => 'G3-SCI', 'subject_name' => 'Science 3', 'grade_level' => 'Grade 3', 'units' => 1],
            ['subject_code' => 'G3-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 3', 'grade_level' => 'Grade 3', 'units' => 1],
            ['subject_code' => 'G3-MAPEH', 'subject_name' => 'MAPEH 3', 'grade_level' => 'Grade 3', 'units' => 1],
            ['subject_code' => 'G3-AP', 'subject_name' => 'Araling Panlipunan 3', 'grade_level' => 'Grade 3', 'units' => 1],

            ['subject_code' => 'G4-ENG', 'subject_name' => 'English 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-FIL', 'subject_name' => 'Filipino 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-MAT', 'subject_name' => 'Mathematics 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-SCI', 'subject_name' => 'Science 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-AP', 'subject_name' => 'Araling Panlipunan 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-MAPEH', 'subject_name' => 'MAPEH 4', 'grade_level' => 'Grade 4', 'units' => 1],
            ['subject_code' => 'G4-EPP', 'subject_name' => 'EPP 4', 'grade_level' => 'Grade 4', 'units' => 1],

            ['subject_code' => 'G5-ENG', 'subject_name' => 'English 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-FIL', 'subject_name' => 'Filipino 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-MAT', 'subject_name' => 'Mathematics 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-SCI', 'subject_name' => 'Science 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-AP', 'subject_name' => 'Araling Panlipunan 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-MAPEH', 'subject_name' => 'MAPEH 5', 'grade_level' => 'Grade 5', 'units' => 1],
            ['subject_code' => 'G5-EPP', 'subject_name' => 'EPP 5', 'grade_level' => 'Grade 5', 'units' => 1],

            ['subject_code' => 'G6-ENG', 'subject_name' => 'English 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-FIL', 'subject_name' => 'Filipino 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-MAT', 'subject_name' => 'Mathematics 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-SCI', 'subject_name' => 'Science 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-AP', 'subject_name' => 'Araling Panlipunan 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-MAPEH', 'subject_name' => 'MAPEH 6', 'grade_level' => 'Grade 6', 'units' => 1],
            ['subject_code' => 'G6-EPP', 'subject_name' => 'EPP 6', 'grade_level' => 'Grade 6', 'units' => 1],

            ['subject_code' => 'G7-ENG', 'subject_name' => 'English 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-FIL', 'subject_name' => 'Filipino 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-MAT', 'subject_name' => 'Mathematics 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-SCI', 'subject_name' => 'Science 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-AP', 'subject_name' => 'Araling Panlipunan 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-MAPEH', 'subject_name' => 'MAPEH 7', 'grade_level' => 'Grade 7', 'units' => 1],
            ['subject_code' => 'G7-TLE', 'subject_name' => 'TLE 7', 'grade_level' => 'Grade 7', 'units' => 1],

            ['subject_code' => 'G8-ENG', 'subject_name' => 'English 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-FIL', 'subject_name' => 'Filipino 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-MAT', 'subject_name' => 'Mathematics 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-SCI', 'subject_name' => 'Science 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-AP', 'subject_name' => 'Araling Panlipunan 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-MAPEH', 'subject_name' => 'MAPEH 8', 'grade_level' => 'Grade 8', 'units' => 1],
            ['subject_code' => 'G8-TLE', 'subject_name' => 'TLE 8', 'grade_level' => 'Grade 8', 'units' => 1],

            ['subject_code' => 'G9-ENG', 'subject_name' => 'English 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-FIL', 'subject_name' => 'Filipino 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-MAT', 'subject_name' => 'Mathematics 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-SCI', 'subject_name' => 'Science 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-AP', 'subject_name' => 'Araling Panlipunan 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-MAPEH', 'subject_name' => 'MAPEH 9', 'grade_level' => 'Grade 9', 'units' => 1],
            ['subject_code' => 'G9-TLE', 'subject_name' => 'TLE 9', 'grade_level' => 'Grade 9', 'units' => 1],

            ['subject_code' => 'G10-ENG', 'subject_name' => 'English 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-FIL', 'subject_name' => 'Filipino 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-MAT', 'subject_name' => 'Mathematics 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-SCI', 'subject_name' => 'Science 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-AP', 'subject_name' => 'Araling Panlipunan 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-ESP', 'subject_name' => 'Edukasyon sa Pagpapakatao 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-MAPEH', 'subject_name' => 'MAPEH 10', 'grade_level' => 'Grade 10', 'units' => 1],
            ['subject_code' => 'G10-TLE', 'subject_name' => 'TLE 10', 'grade_level' => 'Grade 10', 'units' => 1],

            ['subject_code' => 'SHS-OC', 'subject_name' => 'Oral Communication', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-RW', 'subject_name' => 'Reading and Writing', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-21CL', 'subject_name' => '21st Century Literature from the Philippines and the World', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-CPAR', 'subject_name' => 'Contemporary Philippine Arts from the Regions', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-MIL', 'subject_name' => 'Media and Information Literacy', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-GMATH', 'subject_name' => 'General Mathematics', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-STAT', 'subject_name' => 'Statistics and Probability', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-ELS', 'subject_name' => 'Earth and Life Science', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-PSCI', 'subject_name' => 'Physical Science', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-PD', 'subject_name' => 'Personal Development', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-UCSP', 'subject_name' => 'Understanding Culture, Society and Politics', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-PEH', 'subject_name' => 'Physical Education and Health', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-EAPP', 'subject_name' => 'English for Academic and Professional Purposes', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'SHS-FPL', 'subject_name' => 'Filipino sa Piling Larang Akademik', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'SHS-ENTREP', 'subject_name' => 'Entrepreneurship', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'SHS-EMTECH', 'subject_name' => 'Empowerment Technologies', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'SHS-PR1', 'subject_name' => 'Practical Research 1', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'SHS-PR2', 'subject_name' => 'Practical Research 2', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'SHS-III', 'subject_name' => 'Inquiries, Investigations and Immersion', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'STEM-PCAL', 'subject_name' => 'Pre-Calculus', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'STEM-BCAL', 'subject_name' => 'Basic Calculus', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'STEM-BIO1', 'subject_name' => 'General Biology 1', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'STEM-CHEM1', 'subject_name' => 'General Chemistry 1', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'STEM-PHY1', 'subject_name' => 'General Physics 1', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'ABM-BMATH', 'subject_name' => 'Business Mathematics', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'ABM-OAM', 'subject_name' => 'Organization and Management', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'ABM-FABM1', 'subject_name' => 'Fundamentals of Accountancy, Business and Management 1', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'ABM-FABM2', 'subject_name' => 'Fundamentals of Accountancy, Business and Management 2', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'HUMSS-DISS', 'subject_name' => 'Disciplines and Ideas in the Social Sciences', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'HUMSS-DIASS', 'subject_name' => 'Disciplines and Ideas in the Applied Social Sciences', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'HUMSS-CREW', 'subject_name' => 'Creative Writing', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'HUMSS-TNCT', 'subject_name' => 'Trends, Networks, and Critical Thinking in the 21st Century', 'grade_level' => 'Grade 12', 'units' => 1],
            ['subject_code' => 'GAS-HGP', 'subject_name' => 'Humanities and Social Sciences General Preparation', 'grade_level' => 'Grade 11', 'units' => 1],
            ['subject_code' => 'GAS-ORG', 'subject_name' => 'Organization and General Studies', 'grade_level' => 'Grade 12', 'units' => 1],
        ];

        foreach ($subjects as $subject) {
            Subject::updateOrCreate(
                ['subject_code' => $subject['subject_code']],
                [
                    'subject_name' => $subject['subject_name'],
                    'grade_level' => $subject['grade_level'],
                    'units' => $subject['units'],
                ]
            );
        }
    }
}
