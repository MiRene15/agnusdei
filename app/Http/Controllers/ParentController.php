<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\ParentModel;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Support\Facades\Auth;

class ParentController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $parent = ParentModel::where('user_id', $user->id)->first();

        $children = collect();
        $totalChildren = 0;
        $totalBalance = 0;

        if ($parent) {
            $children = Student::where('parent_id', $parent->id)->get();
            $totalChildren = $children->count();

            $totalBalance = TuitionFee::whereIn('student_id', $children->pluck('id'))
                ->sum('balance');
        }

        return view('ParentDashboard.dashboard', compact(
            'parent',
            'children',
            'totalChildren',
            'totalBalance'
        ));
    }

    public function children()
    {
        $user = Auth::user();

        $parent = ParentModel::where('user_id', $user->id)->first();

        $children = collect();

        if ($parent) {
            $children = Student::where('parent_id', $parent->id)->get();
        }

        return view('ParentDashboard.children', compact('parent', 'children'));
    }

    public function grades()
    {
        $user = Auth::user();

        $parent = ParentModel::where('user_id', $user->id)->first();

        $children = collect();
        $gradesByChild = collect();

        if ($parent) {
            $children = Student::where('parent_id', $parent->id)->get();

            $gradesByChild = $children->mapWithKeys(function ($child) {
                $enrollmentIds = Enrollment::where('student_id', $child->id)->pluck('id');

                $grades = Grade::with('enrollment.class.subject')
                    ->whereIn('enrollment_id', $enrollmentIds)
                    ->get();

                return [$child->id => $grades];
            });
        }

        return view('ParentDashboard.grades', compact('parent', 'children', 'gradesByChild'));
    }

    public function billing()
    {
        $user = Auth::user();

        $parent = ParentModel::where('user_id', $user->id)->first();

        $children = collect();
        $billingByChild = collect();
        $paymentsByChild = collect();

        if ($parent) {
            $children = Student::where('parent_id', $parent->id)->get();

            $billingByChild = $children->mapWithKeys(function ($child) {
                $billing = TuitionFee::where('student_id', $child->id)
                    ->latest()
                    ->first();

                return [$child->id => $billing];
            });

            $paymentsByChild = $children->mapWithKeys(function ($child) {
                $billing = TuitionFee::where('student_id', $child->id)
                    ->latest()
                    ->first();

                $payments = collect();

                if ($billing) {
                    $payments = Payment::where('tuition_fee_id', $billing->id)
                        ->latest('payment_date')
                        ->get();
                }

                return [$child->id => $payments];
            });
        }

        return view('ParentDashboard.billing', compact(
            'parent',
            'children',
            'billingByChild',
            'paymentsByChild'
        ));
    }
}