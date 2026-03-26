<?php

namespace App\Support;

use App\Models\Student;
use App\Models\TuitionFee;
use Carbon\Carbon;

class TuitionPlanner
{
    public static function shsTracks(): array
    {
        return ['STEM', 'ABM', 'HUMSS', 'GAS'];
    }

    public static function requiresShsTrack(?string $gradeLevel): bool
    {
        return in_array(trim((string) $gradeLevel), ['Grade 11', 'Grade 12'], true);
    }

    public static function normalizeTrack(?string $track): ?string
    {
        $track = strtoupper(trim((string) $track));

        return in_array($track, self::shsTracks(), true) ? $track : null;
    }

    public static function currentSchoolYear(): string
    {
        return now()->year . '-' . (now()->year + 1);
    }

    public static function planFor(?string $gradeLevel, ?string $track = null): array
    {
        $gradeLevel = trim((string) $gradeLevel);
        $track = self::normalizeTrack($track);

        $feeMap = [
            'Nursery' => ['total' => 18000.00, 'down' => 1500.00],
            'Kinder' => ['total' => 21000.00, 'down' => 1500.00],
            'Grade 1' => ['total' => 24748.00, 'down' => 1500.00],
            'Grade 2' => ['total' => 24748.00, 'down' => 1500.00],
            'Grade 3' => ['total' => 25748.00, 'down' => 1500.00],
            'Grade 4' => ['total' => 26748.00, 'down' => 1500.00],
            'Grade 5' => ['total' => 26748.00, 'down' => 1500.00],
            'Grade 6' => ['total' => 26748.00, 'down' => 1500.00],
            'Grade 7' => ['total' => 31700.00, 'down' => 1500.00],
            'Grade 8' => ['total' => 31700.00, 'down' => 1500.00],
            'Grade 9' => ['total' => 31700.00, 'down' => 1500.00],
            'Grade 10' => ['total' => 31700.00, 'down' => 1500.00],
            'Grade 11' => ['total' => 31700.00, 'down' => 1500.00],
            'Grade 12' => ['total' => 31700.00, 'down' => 1500.00],
            'Grade 7 ESC' => ['total' => 22700.00, 'down' => 1500.00],
            'Grade 8 ESC' => ['total' => 22700.00, 'down' => 1500.00],
            'Grade 9 ESC' => ['total' => 22700.00, 'down' => 1500.00],
            'Grade 10 ESC' => ['total' => 22700.00, 'down' => 1500.00],
        ];

        $selected = $feeMap[$gradeLevel] ?? ['total' => 0.00, 'down' => 0.00];

        if (self::requiresShsTrack($gradeLevel) && $track === null) {
            $selected = ['total' => 31700.00, 'down' => 1500.00];
        }

        $downPayment = $selected['total'] > 0 ? max(1500.00, (float) $selected['down']) : 0.00;
        $monthlyPayment = $selected['total'] > 0
            ? round(max(0, ($selected['total'] - $downPayment) / 10), 2)
            : 0.00;

        return [
            'total_amount' => round((float) $selected['total'], 2),
            'down_payment_required' => round($downPayment, 2),
            'monthly_payment' => round($monthlyPayment, 2),
            'installments' => $selected['total'] > 0 ? 10 : 0,
            'track' => $track,
        ];
    }

    public static function normalizePaymentPlan(?string $paymentPlan): string
    {
        $paymentPlan = strtolower(trim((string) $paymentPlan));

        return match ($paymentPlan) {
            'cash', 'monthly', 'alternative' => $paymentPlan,
            default => 'monthly',
        };
    }

    public static function automaticDiscounts(Student $student, ?string $paymentPlan = null, ?float $baseTuition = null): array
    {
        $paymentPlan = self::normalizePaymentPlan($paymentPlan);
        $baseTuition = round((float) ($baseTuition ?? self::planFor($student->grade_level, $student->shs_track)['total_amount']), 2);
        $candidates = [];
        $honorRank = (int) ($student->honor_rank ?? 0);
        $schoolType = strtolower(trim((string) $student->previous_school_type));
        $gradeLevel = trim((string) $student->grade_level);

        if ($honorRank > 0) {
            $honorRate = 0.0;
            $honorLabel = null;

            if ($gradeLevel === 'Grade 7' && $schoolType === 'public' && in_array($honorRank, [1, 2], true)) {
                $honorRate = 100.0;
                $honorLabel = 'Grade 7 Public School Honor';
            } elseif (in_array($gradeLevel, ['Kinder', 'Grade 1', 'Grade 2', 'Grade 3', 'Grade 4', 'Grade 5', 'Grade 6'], true)) {
                $honorRate = match ($honorRank) {
                    1 => 100.0,
                    2 => 50.0,
                    3 => 25.0,
                    default => 0.0,
                };
                $honorLabel = $honorRate > 0 ? 'Honors Program Discount' : null;
            } elseif (in_array($gradeLevel, ['Grade 7', 'Grade 8', 'Grade 9', 'Grade 10'], true)) {
                $honorRate = match ($honorRank) {
                    1 => 100.0,
                    2 => 75.0,
                    3 => 50.0,
                    default => 0.0,
                };
                $honorLabel = $honorRate > 0 ? 'Honors Program Discount' : null;
            }

            if ($honorRate > 0 && $honorLabel) {
                $candidates[] = [
                    'type' => 'honors',
                    'rate' => $honorRate,
                    'amount' => round($baseTuition * ($honorRate / 100), 2),
                    'notes' => $honorLabel . ' applied based on automatic rank rules.',
                ];
            }
        }

        $familyRate = self::familyDiscountRate($student);
        if ($familyRate > 0) {
            $candidates[] = [
                'type' => 'family',
                'rate' => $familyRate,
                'amount' => round($baseTuition * ($familyRate / 100), 2),
                'notes' => 'Family discount applied automatically based on linked siblings under the same parent account.',
            ];
        }

        if ($paymentPlan === 'cash') {
            $candidates[] = [
                'type' => 'cash_plan',
                'rate' => 10.0,
                'amount' => round($baseTuition * 0.10, 2),
                'notes' => 'Plan A cash payment discount applied automatically.',
            ];
        }

        if (empty($candidates)) {
            return [
                'type' => null,
                'rate' => 0.0,
                'amount' => 0.0,
                'notes' => null,
            ];
        }

        usort($candidates, fn ($left, $right) => $right['amount'] <=> $left['amount']);

        return $candidates[0];
    }

    public static function familyDiscountRate(Student $student): float
    {
        if (!$student->parent_id) {
            return 0.0;
        }

        $schoolYear = $student->school_year ?: self::currentSchoolYear();
        $siblings = Student::where('parent_id', $student->parent_id)
            ->where('school_year', $schoolYear)
            ->where('is_transferred', false)
            ->orderBy('id')
            ->pluck('id')
            ->values();

        $position = $siblings->search(fn ($id) => (int) $id === (int) $student->id);
        if ($position === false) {
            return 0.0;
        }

        return match ($position + 1) {
            2 => 10.0,
            default => ($position + 1 >= 3 ? 15.0 : 0.0),
        };
    }

    public static function approvedCarryoverAmount(Student $student, string $schoolYear): float
    {
        return round((float) TuitionFee::where('student_id', $student->id)
            ->where('school_year', '!=', $schoolYear)
            ->where('balance', '>', 0)
            ->where('carryover_approved', true)
            ->where(function ($query) use ($schoolYear) {
                $query->whereNull('carried_over_to_school_year')
                    ->orWhere('carried_over_to_school_year', $schoolYear);
            })
            ->sum('balance'), 2);
    }

    public static function billingPayload(Student $student, string $schoolYear, float $existingPaidAmount = 0.00, ?string $paymentPlan = null): array
    {
        $plan = self::planFor($student->grade_level, $student->shs_track);
        $paymentPlan = self::normalizePaymentPlan($paymentPlan);
        $discount = self::automaticDiscounts($student, $paymentPlan, $plan['total_amount']);
        $netTuition = round(max(0, $plan['total_amount'] - $discount['amount']), 2);
        $carryover = self::approvedCarryoverAmount($student, $schoolYear);
        $totalDue = round($netTuition + $carryover, 2);
        $paidAmount = round($existingPaidAmount, 2);
        $balance = round(max(0, $totalDue - $paidAmount), 2);
        $downPaymentRequired = $paymentPlan === 'cash'
            ? $totalDue
            : ($netTuition > 0 ? max(1500.00, (float) $plan['down_payment_required']) : 0.00);
        $monthlyPayment = $paymentPlan === 'cash'
            ? 0.00
            : ($netTuition > 0 ? round(max(0, ($netTuition - $downPaymentRequired) / 10), 2) : 0.00);

        return [
            'payment_plan' => $paymentPlan,
            'total_amount' => $netTuition,
            'down_payment_required' => round($downPaymentRequired, 2),
            'monthly_payment' => round($monthlyPayment, 2),
            'previous_balance' => $carryover,
            'discount_type' => $discount['type'],
            'discount_rate' => $discount['rate'],
            'discount_amount' => $discount['amount'],
            'discount_notes' => $discount['notes'],
            'total_due' => $totalDue,
            'paid_amount' => $paidAmount,
            'balance' => $balance,
            'due_date' => self::nextMonthlyDueDate(),
            'status' => $balance <= 0 ? 'paid' : ($paidAmount > 0 ? 'partial' : ($totalDue > 0 ? 'unpaid' : 'voucher')),
            'is_downpayment_cleared' => $paidAmount >= $plan['down_payment_required'],
        ];
    }

    public static function nextMonthlyDueDate(?Carbon $baseDate = null): Carbon
    {
        $baseDate = ($baseDate ?: now())->copy()->startOfDay();
        $dueDate = $baseDate->copy()->day(15);

        if ($baseDate->day > 15) {
            $dueDate = $baseDate->copy()->addMonthNoOverflow()->day(15);
        }

        return $dueDate;
    }
}
