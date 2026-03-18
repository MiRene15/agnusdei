<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Http\Request;

class CashierController extends Controller
{
    public function dashboard()
    {
        $totalStudentsWithBilling = TuitionFee::distinct('student_id')->count('student_id');
        $totalCollected = Payment::sum('amount');
        $totalOutstanding = TuitionFee::sum('balance');
        $recentPayments = Payment::with('tuitionFee.student')
            ->latest('payment_date')
            ->take(10)
            ->get();

        return view('CashierDashboard.dashboard', compact(
            'totalStudentsWithBilling',
            'totalCollected',
            'totalOutstanding',
            'recentPayments'
        ));
    }

    public function billing(Request $request)
    {
        $query = TuitionFee::with('student');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('student', function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $billings = $query->latest()->paginate(10);

        return view('CashierDashboard.billing', compact('billings'));
    }

    public function payments(Request $request)
    {
        $query = Payment::with('tuitionFee.student');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('tuitionFee.student', function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            })->orWhere('reference_no', 'like', "%{$search}%");
        }

        $payments = $query->latest('payment_date')->paginate(10);

        return view('CashierDashboard.payments', compact('payments'));
    }

    public function reports()
    {
        $totalCollected = Payment::sum('amount');
        $totalOutstanding = TuitionFee::sum('balance');
        $paymentCount = Payment::count();
        $billingCount = TuitionFee::count();

        return view('CashierDashboard.reports', compact(
            'totalCollected',
            'totalOutstanding',
            'paymentCount',
            'billingCount'
        ));
    }
}