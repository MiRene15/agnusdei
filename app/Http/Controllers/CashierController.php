<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Payment;
use App\Models\TuitionFee;
use App\Support\TuitionPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CashierController extends Controller
{
    private array $columnCache = [];

    public function dashboard()
    {
        $totalCollected = Payment::sum('amount');
        $paymentCount = Payment::count();
        $pendingAccounts = TuitionFee::where('balance', '>', 0)->count();
        $cashCollections = Payment::whereRaw('LOWER(payment_method) = ?', ['cash'])->sum('amount');

        return view('CashierDashboard.dashboard', compact('totalCollected', 'paymentCount', 'pendingAccounts', 'cashCollections'));
    }

    public function billing(Request $request)
    {
        $query = TuitionFee::with('student');

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%")
                        ->orWhere('shs_track', 'like', "%{$search}%");
                })->orWhere('school_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = strtolower(trim($request->status));
            if (in_array($status, ['paid', 'partial', 'unpaid', 'voucher'], true)) {
                $query->whereRaw('LOWER(status) = ?', [$status]);
            } elseif ($status === 'cleared' && $this->hasTuitionColumn('is_downpayment_cleared')) {
                $query->where('is_downpayment_cleared', true);
            } elseif ($status === 'not_cleared' && $this->hasTuitionColumn('is_downpayment_cleared')) {
                $query->where('is_downpayment_cleared', false);
            }
        }

        $billings = $query->latest()->paginate(10)->withQueryString();

        return view('CashierDashboard.billing', compact('billings'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['tuitionFee.student', 'cashier']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('reference_no', 'like', "%{$search}%");

                if ($this->hasPaymentColumn('receipt_number')) {
                    $q->orWhere('receipt_number', 'like', "%{$search}%");
                }

                $q->orWhere('payment_method', 'like', "%{$search}%");

                if ($this->hasPaymentColumn('payment_label')) {
                    $q->orWhere('payment_label', 'like', "%{$search}%");
                }

                $q->orWhere('received_by', 'like', "%{$search}%");

                if ($this->hasPaymentColumn('notes')) {
                    $q->orWhere('notes', 'like', "%{$search}%");
                }

                $q->orWhereHas('tuitionFee.student', function ($studentQuery) use ($search) {
                    $studentQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('student_number', 'like', "%{$search}%")
                        ->orWhere('lrn', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('payment_method')) {
            $query->whereRaw('LOWER(payment_method) = ?', [strtolower(trim($request->payment_method))]);
        }

        $payments = $query->latest('payment_date')->paginate(10)->withQueryString();

        return view('CashierDashboard.payments', compact('payments'));
    }

    public function createPayment($tuitionFeeId)
    {
        $tuition = TuitionFee::with(['student.admission.requirements'])->findOrFail($tuitionFeeId);
        $selectedPlan = TuitionPlanner::normalizePaymentPlan(old('payment_plan', $tuition->payment_plan ?: 'monthly'));
        $planOptions = collect(['cash', 'monthly', 'alternative'])
            ->mapWithKeys(fn (string $plan) => [$plan => $this->buildPlanPreview($tuition, $plan)])
            ->all();
        $remainingForUnlock = max(0, (float) $planOptions[$selectedPlan]['payload']['down_payment_required'] - (float) $tuition->paid_amount);

        return view('CashierDashboard.create-payment', compact('tuition', 'remainingForUnlock', 'planOptions', 'selectedPlan'));
    }

    public function storePayment(Request $request, $tuitionFeeId)
    {
        $request->validate([
            'payment_plan' => 'required|in:cash,monthly,alternative',
            'cash_tendered' => 'required|numeric|min:1',
            'notes' => 'nullable|string|max:255',
        ]);

        $tuition = TuitionFee::with('student')->findOrFail($tuitionFeeId);
        $paymentPlan = TuitionPlanner::normalizePaymentPlan($request->payment_plan ?: $tuition->payment_plan);

        if ((float) $tuition->paid_amount > 0 && $tuition->payment_plan && $paymentPlan !== $tuition->payment_plan) {
            return back()->withErrors(['payment_plan' => 'Payment plan can only be changed before the first payment is posted.'])->withInput();
        }

        $payload = TuitionPlanner::billingPayload(
            $tuition->student,
            $tuition->school_year,
            (float) $tuition->paid_amount,
            $paymentPlan
        );
        $amountToApply = $this->resolveAppliedAmount($tuition, $payload, $paymentPlan);
        $paymentLabel = $this->resolvePaymentLabel($tuition, $payload, $paymentPlan, $amountToApply);

        $tuition->fill(TuitionPlanner::persistableTuitionPayload($payload));
        $tuition->save();

        if ((float) $tuition->balance <= 0) {
            return back()->withErrors(['cash_tendered' => 'This account is already fully paid.'])->withInput();
        }

        if ($amountToApply <= 0) {
            return back()->withErrors(['cash_tendered' => 'There is no billable amount to post for the selected plan.'])->withInput();
        }

        $cashTendered = round((float) $request->cash_tendered, 2);
        if ($cashTendered < $amountToApply) {
            return back()->withErrors(['cash_tendered' => 'Cash received must be equal to or greater than the amount being applied.'])->withInput();
        }
        $changeAmount = round($cashTendered - $amountToApply, 2);

        $payment = Payment::create([
            'tuition_fee_id' => $tuition->id,
            'payment_date' => now()->toDateString(),
            'amount' => $amountToApply,
            'cash_tendered' => $cashTendered,
            'change_amount' => $changeAmount,
            'payment_method' => 'Cash',
            'payment_label' => $paymentLabel,
            'reference_no' => 'PAY-' . strtoupper(uniqid()),
            'received_by' => $this->cashierAuditLabel(Auth::user()),
            'received_by_user_id' => Auth::id(),
            'receipt_number' => $this->generateReceiptNumber(),
            'notes' => $request->notes,
        ]);

        $tuition->paid_amount = round((float) $tuition->paid_amount + $amountToApply, 2);
        $tuition->balance = max(0, (float) $tuition->total_due - (float) $tuition->paid_amount);
        $tuition->is_downpayment_cleared = (float) $tuition->paid_amount >= (float) $tuition->down_payment_required;
        $tuition->status = $tuition->balance <= 0 ? 'paid' : ((float) $tuition->paid_amount > 0 ? 'partial' : 'unpaid');
        $tuition->save();

        $this->syncStudentPortalAccess($tuition);

        ActivityLog::record(Auth::id(), 'payment', 'receive', 'Payment', $payment->id, 'Received payment of ' . number_format((float) $payment->amount, 2) . ' for tuition #' . $tuition->id, $request->ip());

        return redirect()->route('cashier.payments.receipt', $payment->id)->with('success', 'Payment recorded successfully. Receipt #: ' . $payment->receipt_number);
    }

    public function verifyShsVoucher(Request $request, $tuitionFeeId)
    {
        $tuition = TuitionFee::with(['student.admission.requirements'])->findOrFail($tuitionFeeId);
        $student = $tuition->student;

        if (!$student || !in_array(trim((string) $student->grade_level), ['Grade 11', 'Grade 12'], true)) {
            return back()->with('error', 'SHS voucher verification only applies to Senior High students.');
        }

        if ($tuition->voucher_status === 'verified') {
            return back()->with('success', 'This SHS voucher has already been fully verified.');
        }

        if (!in_array($tuition->voucher_status, ['registrar_verified', 'verified'], true)) {
            return back()->with('error', 'Registrar confirmation is required before cashier verification.');
        }

        if (!$student->admission || !$student->admission->requirements()->where('requirement_name', 'SHS Voucher')->where('submitted', 1)->exists()) {
            return back()->with('error', 'The SHS voucher document must be submitted before cashier verification.');
        }

        $voucherCredit = round(min(
            (float) $tuition->balance,
            max(0, (float) $tuition->down_payment_required - (float) $tuition->paid_amount)
        ), 2);

        if ($voucherCredit <= 0) {
            return back()->with('error', 'This account already has the required down payment cleared.');
        }

        $payment = Payment::create([
            'tuition_fee_id' => $tuition->id,
            'payment_date' => now()->toDateString(),
            'amount' => $voucherCredit,
            'cash_tendered' => null,
            'change_amount' => null,
            'payment_method' => 'voucher',
            'payment_label' => 'SHS Voucher Credit',
            'reference_no' => 'VCR-' . strtoupper(uniqid()),
            'received_by' => $this->cashierAuditLabel(Auth::user()),
            'received_by_user_id' => Auth::id(),
            'receipt_number' => $this->generateReceiptNumber(),
            'notes' => 'Verified SHS voucher credit applied to required down payment.',
        ]);

        $tuition->paid_amount = round((float) $tuition->paid_amount + $voucherCredit, 2);
        $tuition->balance = max(0, round((float) $tuition->total_due - (float) $tuition->paid_amount, 2));
        $tuition->is_downpayment_cleared = (float) $tuition->paid_amount >= (float) $tuition->down_payment_required;
        $tuition->voucher_status = 'verified';
        $tuition->voucher_cashier_verified_at = now();
        $tuition->voucher_cashier_verified_by = Auth::id();
        $tuition->status = $tuition->balance <= 0 ? 'paid' : 'partial';
        $tuition->save();

        $this->syncStudentPortalAccess($tuition);

        ActivityLog::record(Auth::id(), 'payment', 'verify_shs_voucher', 'Payment', $payment->id, 'Cashier verified SHS voucher for tuition #' . $tuition->id, $request->ip());

        return redirect()->route('cashier.payments.receipt', $payment->id)->with('success', 'SHS voucher verified and applied successfully.');
    }

    public function reports()
    {
        $totalCollected = Payment::sum('amount');
        $paymentCount = Payment::count();
        $fullyPaid = TuitionFee::where('balance', '<=', 0)->count();
        $withBalance = TuitionFee::where('balance', '>', 0)->count();

        return view('CashierDashboard.reports', compact('totalCollected', 'paymentCount', 'fullyPaid', 'withBalance'));
    }

    public function showReceipt($id)
    {
        $payment = Payment::with(['tuitionFee.student', 'cashier'])->findOrFail($id);

        return view('CashierDashboard.receipt', compact('payment'));
    }

    private function generateReceiptNumber(): string
    {
        do {
            $receipt = 'RCPT-' . now()->format('Y') . '-' . random_int(100000, 999999);
        } while (Payment::where('receipt_number', $receipt)->exists());

        return $receipt;
    }

    private function cashierAuditLabel($user): string
    {
        if (!$user) {
            return 'Unknown Cashier';
        }

        return $user->name . ' [CASH-' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT) . ']';
    }

    private function syncStudentPortalAccess(TuitionFee $tuition): void
    {
        $student = $tuition->student;

        if (!$student) {
            return;
        }

        if ($tuition->is_downpayment_cleared || $tuition->voucher_status === 'verified') {
            $student->update([
                'portal_access_status' => 'unlocked',
                'portal_unlocked_at' => now(),
                'status' => $student->status === 'enrolled' ? 'enrolled' : 'payment_cleared',
            ]);
        }
    }

    private function buildPlanPreview(TuitionFee $tuition, string $paymentPlan): array
    {
        $payload = TuitionPlanner::billingPayload(
            $tuition->student,
            $tuition->school_year,
            (float) $tuition->paid_amount,
            $paymentPlan
        );

        $recommendedAmount = $this->resolveAppliedAmount($tuition, $payload, $paymentPlan);

        return [
            'payload' => $payload,
            'recommended_amount' => $recommendedAmount,
            'payment_label' => $this->resolvePaymentLabel($tuition, $payload, $paymentPlan, $recommendedAmount),
        ];
    }

    private function resolveAppliedAmount(TuitionFee $tuition, array $payload, string $paymentPlan): float
    {
        $balance = round(max(0, (float) $payload['balance']), 2);
        if ($balance <= 0) {
            return 0.0;
        }

        $paidAmount = round((float) $tuition->paid_amount, 2);
        $downPaymentRemaining = round(max(0, (float) $payload['down_payment_required'] - $paidAmount), 2);

        return match ($paymentPlan) {
            'cash' => $balance,
            'monthly' => round(min($balance, $downPaymentRemaining > 0 ? $downPaymentRemaining : (float) $payload['monthly_payment']), 2),
            default => round(min($balance, $downPaymentRemaining > 0 ? $downPaymentRemaining : max((float) $payload['monthly_payment'], round($balance / 4, 2))), 2),
        };
    }

    private function resolvePaymentLabel(TuitionFee $tuition, array $payload, string $paymentPlan, float $amountToApply): string
    {
        $balance = round(max(0, (float) $payload['balance']), 2);
        $paidAmount = round((float) $tuition->paid_amount, 2);
        $downPaymentRemaining = round(max(0, (float) $payload['down_payment_required'] - $paidAmount), 2);

        if ($paymentPlan === 'cash' || $amountToApply >= $balance) {
            return 'Full Payment';
        }

        if ($downPaymentRemaining > 0) {
            return 'Down Payment';
        }

        return $paymentPlan === 'alternative' ? 'Plan C Installment' : 'Monthly Installment';
    }

    private function hasPaymentColumn(string $column): bool
    {
        return $this->hasColumn('payments', $column);
    }

    private function hasTuitionColumn(string $column): bool
    {
        return $this->hasColumn('tuition_fees', $column);
    }

    private function hasColumn(string $table, string $column): bool
    {
        $key = $table . '.' . $column;

        if (!array_key_exists($key, $this->columnCache)) {
            $this->columnCache[$key] = Schema::hasColumn($table, $column);
        }

        return $this->columnCache[$key];
    }
}
