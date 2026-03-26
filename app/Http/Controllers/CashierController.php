<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\TuitionFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function dashboard()
    {
        $totalCollected = Payment::sum('amount');
        $paymentCount = Payment::count();
        $pendingAccounts = TuitionFee::where('balance', '>', 0)->count();

        return view('CashierDashboard.dashboard', compact(
            'totalCollected',
            'paymentCount',
            'pendingAccounts'
        ));
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
                        ->orWhere('lrn', 'like', "%{$search}%");
                })->orWhere('school_year', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = strtolower(trim($request->status));

            if (in_array($status, ['paid', 'partial', 'unpaid', 'voucher'])) {
                $query->whereRaw('LOWER(status) = ?', [$status]);
            } elseif ($status === 'cleared') {
                $query->where('is_downpayment_cleared', true);
            } elseif ($status === 'not_cleared') {
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
                $q->where('reference_no', 'like', "%{$search}%")
                    ->orWhere('receipt_number', 'like', "%{$search}%")
                    ->orWhere('payment_method', 'like', "%{$search}%")
                    ->orWhere('received_by', 'like', "%{$search}%")
                    ->orWhereHas('tuitionFee.student', function ($studentQuery) use ($search) {
                        $studentQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('student_number', 'like', "%{$search}%")
                            ->orWhere('lrn', 'like', "%{$search}%");
                    });
            });
        }

        $payments = $query->latest('payment_date')->paginate(10)->withQueryString();

        return view('CashierDashboard.payments', compact('payments'));
    }

    public function createPayment($tuitionFeeId)
    {
        $tuition = TuitionFee::with('student')->findOrFail($tuitionFeeId);

        return view('CashierDashboard.create-payment', compact('tuition'));
    }

    public function storePayment(Request $request, $tuitionFeeId)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|string|max:100',
        ]);

        $tuition = TuitionFee::with('student')->findOrFail($tuitionFeeId);

        if ((float) $tuition->balance <= 0) {
            return back()->withErrors([
                'amount' => 'This account is already fully paid.',
            ])->withInput();
        }

        if ((float) $request->amount > (float) $tuition->balance) {
            return back()->withErrors([
                'amount' => 'Payment amount cannot be greater than the current balance.',
            ])->withInput();
        }

        $payment = Payment::create([
            'tuition_fee_id' => $tuition->id,
            'payment_date' => now()->toDateString(),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'reference_no' => 'PAY-' . strtoupper(uniqid()),
            'received_by' => Auth::user()->name,
            'received_by_user_id' => Auth::id(),
            'receipt_number' => $this->generateReceiptNumber(),
        ]);

        $tuition->paid_amount = (float) $tuition->paid_amount + (float) $request->amount;
        $tuition->balance = max(0, (float) $tuition->total_due - (float) $tuition->paid_amount);
        $tuition->is_downpayment_cleared = (float) $tuition->paid_amount >= (float) $tuition->down_payment_required;
        $tuition->status = $tuition->balance <= 0 ? 'paid' : ((float) $tuition->paid_amount > 0 ? 'partial' : 'unpaid');
        $tuition->save();

        $student = $tuition->student;
        if ($student && $tuition->is_downpayment_cleared) {
            $student->update([
                'portal_access_status' => 'unlocked',
                'portal_unlocked_at' => now(),
            ]);
        }

        return redirect()->route('cashier.payments')->with('success', 'Payment recorded successfully. Receipt #: ' . $payment->receipt_number);
    }

    public function reports()
    {
        $totalCollected = Payment::sum('amount');
        $paymentCount = Payment::count();
        $fullyPaid = TuitionFee::where('balance', '<=', 0)->count();
        $withBalance = TuitionFee::where('balance', '>', 0)->count();

        return view('CashierDashboard.reports', compact(
            'totalCollected',
            'paymentCount',
            'fullyPaid',
            'withBalance'
        ));
    }

    private function generateReceiptNumber(): string
    {
        do {
            $receipt = 'RCPT-' . now()->format('Y') . '-' . random_int(100000, 999999);
        } while (Payment::where('receipt_number', $receipt)->exists());

        return $receipt;
    }
}