<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\TuitionFee;
use App\Support\TuitionPlanner;
use Illuminate\Database\Seeder;

class TuitionFeeSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = TuitionPlanner::currentSchoolYear();

        $students = Student::whereNotNull('grade_level')->get();

        foreach ($students as $student) {
            $effectiveSchoolYear = $student->school_year ?: $schoolYear;
            $existingTuition = TuitionFee::where('student_id', $student->id)
                ->where('school_year', $effectiveSchoolYear)
                ->first();
            $existingPaidAmount = (float) ($existingTuition?->paid_amount ?? 0);
            $paymentPlan = $existingTuition?->payment_plan ?: 'monthly';

            $payload = TuitionPlanner::billingPayload($student, $effectiveSchoolYear, $existingPaidAmount, $paymentPlan);

            TuitionFee::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year' => $effectiveSchoolYear,
                ],
                TuitionPlanner::persistableTuitionPayload($payload)
            );

            if (\Illuminate\Support\Facades\Schema::hasColumn('tuition_fees', 'carryover_approved')
                && \Illuminate\Support\Facades\Schema::hasColumn('tuition_fees', 'carried_over_to_school_year')) {
                TuitionFee::where('student_id', $student->id)
                    ->where('school_year', '!=', $effectiveSchoolYear)
                    ->where('balance', '>', 0)
                    ->where('carryover_approved', true)
                    ->update([
                        'carried_over_to_school_year' => $effectiveSchoolYear,
                    ]);
            }
        }
    }
}
