<?php

namespace Database\Seeders;

use App\Models\Enrollment;
use App\Models\Grade;
use Illuminate\Database\Seeder;

class DefaultGradesSeeder extends Seeder
{
    public function run(): void
    {
        $periods = ['1st Quarter', '2nd Quarter'];

        Enrollment::with(['student', 'class.teacher'])
            ->whereHas('class', fn ($query) => $query->whereNotNull('teacher_id'))
            ->chunk(200, function ($enrollments) use ($periods) {
                foreach ($enrollments as $enrollment) {
                    foreach ($periods as $periodIndex => $period) {
                        $seed = abs(crc32($enrollment->id . '|' . $period));
                        $seatwork = $this->scoreFromSeed($seed, 76, 97);
                        $quiz = $this->scoreFromSeed($seed + 17, 75, 98);
                        $exam = $this->scoreFromSeed($seed + 33, 74, 99);
                        $finalGrade = round(($seatwork * 0.30) + ($quiz * 0.30) + ($exam * 0.40), 2);

                        Grade::updateOrCreate(
                            [
                                'enrollment_id' => $enrollment->id,
                                'grading_period' => $period,
                            ],
                            [
                                'seatwork_score' => $seatwork,
                                'quiz_score' => $quiz,
                                'exam_score' => $exam,
                                'final_grade' => $finalGrade,
                                'grade' => $finalGrade,
                                'remarks' => $finalGrade >= 75 ? 'Passed' : 'Needs Intervention',
                                'created_at' => now()->subMonths(3 - $periodIndex),
                                'updated_at' => now()->subMonths(3 - $periodIndex),
                            ]
                        );
                    }
                }
            });
    }

    private function scoreFromSeed(int $seed, int $min, int $max): float
    {
        $range = max(1, $max - $min);

        return (float) ($min + ($seed % ($range + 1)));
    }
}
