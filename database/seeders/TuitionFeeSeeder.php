<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Database\Seeder;

class TuitionFeeSeeder extends Seeder
{
    public function run(): void
    {
        $schoolYear = date('Y') . '-' . (date('Y') + 1);

        $feeMap = [
            'Kinder' => ['total' => 21000, 'down' => 1500, 'monthly' => 1950],
            'Grade 1' => ['total' => 24748, 'down' => 1500, 'monthly' => 2325],
            'Grade 2' => ['total' => 24748, 'down' => 1500, 'monthly' => 2325],
            'Grade 3' => ['total' => 25748, 'down' => 1500, 'monthly' => 2425],
            'Grade 4' => ['total' => 26748, 'down' => 1500, 'monthly' => 2525],
            'Grade 5' => ['total' => 26748, 'down' => 1500, 'monthly' => 2525],
            'Grade 6' => ['total' => 26748, 'down' => 1500, 'monthly' => 2525],
            'Grade 7' => ['total' => 31700, 'down' => 1500, 'monthly' => 3020],
            'Grade 8' => ['total' => 31700, 'down' => 1500, 'monthly' => 3020],
            'Grade 9' => ['total' => 31700, 'down' => 1500, 'monthly' => 3020],
            'Grade 10' => ['total' => 31700, 'down' => 1500, 'monthly' => 3020],

            'Grade 7 ESC' => ['total' => 22700, 'down' => 1500, 'monthly' => 2120],
            'Grade 8 ESC' => ['total' => 22700, 'down' => 1500, 'monthly' => 2120],
            'Grade 9 ESC' => ['total' => 22700, 'down' => 1500, 'monthly' => 2120],
            'Grade 10 ESC' => ['total' => 22700, 'down' => 1500, 'monthly' => 2120],

            'Grade 11' => ['total' => 0, 'down' => 0, 'monthly' => 0],
            'Grade 12' => ['total' => 0, 'down' => 0, 'monthly' => 0],
        ];

        $students = Student::whereNotNull('grade_level')->get();

        foreach ($students as $student) {
            $gradeLevel = trim((string) $student->grade_level);

            if (!isset($feeMap[$gradeLevel])) {
                continue;
            }

            // 🔥 carry over previous balance
            $previousBalance = TuitionFee::where('student_id', $student->id)
                ->where('school_year', '!=', ($student->school_year ?: $schoolYear))
                ->sum('balance');

            $total = $feeMap[$gradeLevel]['total'];
            $down = $feeMap[$gradeLevel]['down'];
            $monthly = $feeMap[$gradeLevel]['monthly'];

            $totalDue = $total + $previousBalance;

            TuitionFee::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year' => $student->school_year ?: $schoolYear,
                ],
                [
                    'total_amount' => $total,
                    'down_payment_required' => $down,
                    'monthly_payment' => $monthly,
                    'previous_balance' => $previousBalance,
                    'total_due' => $totalDue,
                    'paid_amount' => 0,
                    'balance' => $totalDue,
                    'due_date' => now()->addMonth(),
                    'status' => $totalDue > 0 ? 'unpaid' : 'voucher',
                    'is_downpayment_cleared' => false,
                ]
            );
        }
    }
}