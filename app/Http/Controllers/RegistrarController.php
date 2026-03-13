<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RegistrarController extends Controller
{
    public function dashboard()
    {
        $totalApplicants = Admission::count();
        $pendingApplicants = Admission::where('status', 'pending')->count();
        $approvedApplicants = Admission::where('status', 'approved')->count();
        $incompleteApplicants = Admission::whereHas('requirements', function ($query) {
            $query->where('submitted', 0);
        })->count();

        $recentAdmissions = Admission::latest()->take(10)->get();

        return view('RegistrarDashboard.dashboard', compact(
            'totalApplicants',
            'pendingApplicants',
            'approvedApplicants',
            'incompleteApplicants',
            'recentAdmissions'
        ));
    }

    public function enrollments(Request $request)
    {
        $query = Admission::with('requirements');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_level')) {
            $query->where('applying_for_grade', $request->grade_level);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $admissions = $query->latest()->paginate(10);

        return view('RegistrarDashboard.enrollments', compact('admissions'));
    }

    public function showEnrollment($id)
    {
        $admission = Admission::with('requirements')->findOrFail($id);

        return view('RegistrarDashboard.show-enrollment', compact('admission'));
    }

    public function approveEnrollment($id)
    {
        $admission = Admission::with('requirements')->findOrFail($id);

        $hasIncompleteRequirements = $admission->requirements()->where('submitted', 0)->exists();

        if ($hasIncompleteRequirements) {
            return back()->with('error', 'Cannot approve. Some requirements are still incomplete.');
        }

        $student = Student::where('admission_id', $admission->id)->first();

        if ($student) {
            $student->update([
                'lrn' => $admission->lrn,
                'first_name' => $admission->first_name,
                'last_name' => $admission->last_name,
                'birth_date' => $admission->birth_date,
                'gender' => $admission->sex,
                'email' => $admission->email,
                'phone' => $admission->phone,
                'address' => $admission->address,
                'grade_level' => $admission->applying_for_grade,
                'school_year' => date('Y') . '-' . (date('Y') + 1),
                'status' => 'approved',
            ]);
        } else {
            Student::create([
                'user_id' => null,
                'parent_id' => null,
                'admission_id' => $admission->id,
                'student_number' => 'STU-' . strtoupper(Str::random(8)),
                'lrn' => $admission->lrn,
                'first_name' => $admission->first_name,
                'last_name' => $admission->last_name,
                'birth_date' => $admission->birth_date,
                'gender' => $admission->sex,
                'email' => $admission->email,
                'phone' => $admission->phone,
                'address' => $admission->address,
                'grade_level' => $admission->applying_for_grade,
                'section' => null,
                'school_year' => date('Y') . '-' . (date('Y') + 1),
                'status' => 'approved',
            ]);
        }

        $admission->update([
            'status' => 'approved',
            'remarks' => 'Approved by registrar',
        ]);

        return back()->with('success', 'Admission approved successfully. Student record created and status updated to approved.');
    }

    public function markIncomplete($id)
    {
        $admission = Admission::findOrFail($id);

        $admission->update([
            'status' => 'incomplete',
            'remarks' => 'Marked incomplete by registrar',
        ]);

        return back()->with('success', 'Admission marked as incomplete.');
    }

    public function students(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('student_number', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->latest()->paginate(10);

        return view('RegistrarDashboard.students', compact('students'));
    }

    public function sectioning()
    {
        $students = Student::where('status', 'approved')->get();

        return view('RegistrarDashboard.section', compact('students'));
    }

    public function updateSection(Request $request, $id)
    {
        $request->validate([
            'section' => 'required|string|max:50',
            'school_year' => 'required|string|max:20',
        ]);

        $student = Student::findOrFail($id);

        $student->update([
            'section' => $request->section,
            'school_year' => $request->school_year,
        ]);

        $classes = Classes::where('grade_level', $student->grade_level)
            ->where('section', $request->section)
            ->where('school_year', $request->school_year)
            ->get();

        foreach ($classes as $class) {
            Enrollment::firstOrCreate(
                [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                ],
                [
                    'enrollment_date' => now(),
                    'status' => 'enrolled',
                ]
            );
        }

        return back()->with('success', 'Student section updated successfully and subjects were assigned automatically.');
    }
}