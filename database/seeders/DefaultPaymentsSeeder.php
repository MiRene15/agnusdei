<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use App\Models\User;
use App\Support\TuitionPlanner;
use Illuminate\Database\Seeder;

class DefaultPaymentsSeeder extends Seeder
{
    public function run(): void
    {
        $cashier = User::where('role', 'cashier')->orderBy('id')->first();
        $tuitionFees = TuitionFee::with('student')->orderBy('id')->get();
        $receiptCounter = 1;

        foreach ($tuitionFees as $index => $tuition) {
            $student = $tuition->student;
            if (!$student instanceof Student) {
                continue;
            }

            $paymentPlan = $this->paymentPlanFor($index);
            $payload = TuitionPlanner::billingPayload($student, $tuition->school_year, 0, $paymentPlan);
            $tuition->fill(TuitionPlanner::persistableTuitionPayload($payload));

            $targetPaid = $this->targetPaidAmount($payload, $paymentPlan, $index);
            $tuition->paid_amount = round($targetPaid, 2);
            $tuition->balance = round(max(0, (float) $payload['total_due'] - $targetPaid), 2);
            $tuition->status = $tuition->balance <= 0 ? 'paid' : ($targetPaid > 0 ? 'partial' : 'unpaid');
            $tuition->is_downpayment_cleared = $targetPaid >= (float) $payload['down_payment_required'];
            $tuition->payment_plan = $paymentPlan;
            $tuition->save();

            Payment::where('tuition_fee_id', $tuition->id)->delete();

            if ($targetPaid > 0) {
                $cashTendered = round($targetPaid + (($index % 3) * 100), 2);
                Payment::create([
                    'tuition_fee_id' => $tuition->id,
                    'payment_date' => now()->subDays($index % 30)->toDateString(),
                    'amount' => round($targetPaid, 2),
                    'cash_tendered' => $cashTendered,
                    'change_amount' => round($cashTendered - $targetPaid, 2),
                    'payment_method' => 'cash',
                    'payment_label' => $this->paymentLabel($payload, $targetPaid),
                    'reference_no' => 'PAY-SEED-' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT),
                    'received_by' => $cashier?->name ?? 'Seeded Cashier',
                    'received_by_user_id' => $cashier?->id,
                    'receipt_number' => 'RCP-SEED-' . str_pad((string) $receiptCounter++, 6, '0', STR_PAD_LEFT),
                    'notes' => 'Seeded demo payment record.',
                ]);
            }

            $student->update([
                'portal_access_status' => $tuition->is_downpayment_cleared ? 'unlocked' : 'locked',
                'portal_unlocked_at' => $tuition->is_downpayment_cleared ? now()->subDays($index % 20) : null,
                'status' => $tuition->is_downpayment_cleared ? 'payment_cleared' : 'active',
            ]);
        }
    }

    private function paymentPlanFor(int $seed): string
    {
        return match ($seed % 3) {
            0 => 'cash',
            1 => 'monthly',
            default => 'alternative',
        };
    }

    private function targetPaidAmount(array $payload, string $paymentPlan, int $seed): float
    {
        $totalDue = (float) $payload['total_due'];
        $downPayment = (float) $payload['down_payment_required'];
        $monthly = (float) $payload['monthly_payment'];

        if ($paymentPlan === 'cash') {
            return round($totalDue, 2);
        }

        return match ($seed % 5) {
            0 => round(min($totalDue, max(500, $downPayment * 0.6)), 2),
            1 => round(min($totalDue, $downPayment), 2),
            2 => round(min($totalDue, $downPayment + $monthly), 2),
            3 => round(min($totalDue, $downPayment + ($monthly * 2)), 2),
            default => round($totalDue, 2),
        };
    }

    private function paymentLabel(array $payload, float $targetPaid): string
    {
        if ($targetPaid >= (float) $payload['total_due']) {
            return 'Full Payment';
        }

        if ($targetPaid <= (float) $payload['down_payment_required']) {
            return 'Down Payment';
        }

        return 'Installment Payment';
    }
}

