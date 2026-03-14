<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentPortalController extends Controller
{
    public function check()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('login')->withErrors([
                'email' => 'Student profile not found.',
            ]);
        }

        if (!$student->admission_id) {
            return redirect()->route('student.admission.create');
        }

        $admission = Admission::with('requirements')->find($student->admission_id);

        if (!$admission) {
            return redirect()->route('student.admission.create');
        }

        $hasIncompleteRequirements = $admission->requirements()->where('submitted', 0)->exists();

        if ($hasIncompleteRequirements) {
            return redirect()->route('student.requirements');
        }

        if (in_array($admission->status, ['pending', 'under_review', 'incomplete'])) {
            return redirect()->route('student.requirements');
        }

        if ($admission->status === 'approved') {
            return redirect()->route('student.dashboard');
        }

        return redirect()->route('student.requirements');
    }

    public function createAdmission()
    {
        return view('StudentDashboard.admission-create');
    }

    public function storeAdmission(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'sex' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'applying_for_grade' => 'required|string|max:50',
            'lrn' => 'required|digits:12|unique:admissions,lrn|unique:students,lrn',
            'previous_school' => 'nullable|string|max:255',
        ], [
            'birth_date.required' => 'Birth date is required.',
            'birth_date.before' => 'Birth date must be earlier than today.',
            'sex.required' => 'Gender is required.',
            'lrn.required' => 'LRN is required.',
            'lrn.digits' => 'LRN must be exactly 12 digits.',
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student) {
            return redirect()->route('login')->withErrors([
                'email' => 'Student profile not found.',
            ]);
        }

        if ($student->admission_id) {
            return redirect()->route('student.requirements')->with('error', 'You already have an admission application.');
        }

        $admission = Admission::create([
            'application_number' => 'APP-' . now()->format('Y') . '-' . strtoupper(Str::random(6)),
            'lrn' => $request->lrn,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'sex' => $request->sex,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'applying_for_grade' => $request->applying_for_grade,
            'previous_school' => $request->previous_school,
            'status' => 'pending',
            'application_date' => now(),
            'remarks' => null,
        ]);

        $defaultRequirements = [
            'Birth Certificate',
            'Report Card',
            'Good Moral Certificate',
            '2x2 ID Picture',
        ];

        foreach ($defaultRequirements as $requirement) {
            $admission->requirements()->create([
                'requirement_name' => $requirement,
                'submitted' => 0,
                'submitted_at' => null,
                'status' => 'pending',
                'remarks' => null,
                'file_path' => null,
            ]);
        }

        $student->update([
            'admission_id' => $admission->id,
            'lrn' => $request->lrn,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'gender' => $request->sex,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'grade_level' => $request->applying_for_grade,
            'status' => 'pending',
        ]);

        return redirect()->route('student.requirements')->with('success', 'Admission application submitted successfully.');
    }

    public function requirements()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || !$student->admission_id) {
            return redirect()->route('student.admission.create')->with('error', 'Please submit your admission application first.');
        }

        $admission = Admission::with('requirements')->find($student->admission_id);

        if (!$admission) {
            return redirect()->route('student.admission.create')->with('error', 'Admission record not found.');
        }

        return view('StudentDashboard.requirements', compact('admission'));
    }

    public function uploadRequirement(Request $request)
    {
        $request->validate([
            'requirement_id' => 'required|exists:admission_requirements,id',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        if (!$student || !$student->admission_id) {
            return redirect()->route('student.admission.create')->with('error', 'Admission record not found.');
        }

        $admission = Admission::find($student->admission_id);

        if (!$admission) {
            return redirect()->route('student.admission.create')->with('error', 'Admission record not found.');
        }

        $requirement = $admission->requirements()->where('id', $request->requirement_id)->first();

        if (!$requirement) {
            return back()->with('error', 'Requirement not found.');
        }

        $path = $request->file('document')->store('requirements', 'public');

        $requirement->update([
            'submitted' => 1,
            'submitted_at' => now(),
            'status' => 'submitted',
            'file_path' => $path,
        ]);

        return back()->with('success', 'Requirement uploaded successfully.');
    }

    public function enrollment()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        return view('StudentDashboard.enrollments', compact('student'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $enrollments = collect();
        $grades = collect();
        $tuition = null;
        $payments = collect();
        $schedule = collect();

        if ($student) {
            $enrollments = Enrollment::with(['class.subject', 'class.schedules'])
                ->where('student_id', $student->id)
                ->get();

            $grades = Grade::with('enrollment.class.subject')
                ->whereIn('enrollment_id', $enrollments->pluck('id'))
                ->get();

            $tuition = TuitionFee::where('student_id', $student->id)
                ->where('school_year', $student->school_year)
                ->first();

            if ($tuition) {
                $payments = Payment::where('tuition_fee_id', $tuition->id)
                    ->latest('payment_date')
                    ->get();
            }

            $schedule = $enrollments->flatMap(function ($enrollment) {
                return $enrollment->class->schedules->map(function ($schedule) use ($enrollment) {
                    return [
                        'subject_name' => $enrollment->class->subject->subject_name ?? '-',
                        'day_of_week' => $schedule->day_of_week,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'room' => $schedule->room,
                    ];
                });
            });
        }

        return view('StudentDashboard.dashboard', compact(
            'student',
            'enrollments',
            'grades',
            'tuition',
            'payments',
            'schedule'
        ));
    }

    public function subjects()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $enrollments = collect();

        if ($student) {
            $enrollments = Enrollment::with('class.subject')
                ->where('student_id', $student->id)
                ->get();
        }

        return view('StudentDashboard.subjects', compact('student', 'enrollments'));
    }

    public function grades()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $grades = collect();

        if ($student) {
            $enrollments = Enrollment::where('student_id', $student->id)->pluck('id');

            $grades = Grade::with('enrollment.class.subject')
                ->whereIn('enrollment_id', $enrollments)
                ->get();
        }

        return view('StudentDashboard.grades', compact('student', 'grades'));
    }

    public function scheduleView()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $schedule = collect();

        if ($student) {
            $enrollments = Enrollment::with(['class.subject', 'class.schedules'])
                ->where('student_id', $student->id)
                ->get();

            $schedule = $enrollments->flatMap(function ($enrollment) {
                return $enrollment->class->schedules->map(function ($schedule) use ($enrollment) {
                    return [
                        'subject_name' => $enrollment->class->subject->subject_name ?? '-',
                        'day_of_week' => $schedule->day_of_week,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'room' => $schedule->room,
                    ];
                });
            });
        }

        return view('StudentDashboard.schedule', compact('student', 'schedule'));
    }

    public function assessment()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();

        $tuition = null;
        $payments = collect();
        $quarterAmount = 0;

        if ($student) {
            $tuition = TuitionFee::where('student_id', $student->id)
                ->where('school_year', $student->school_year)
                ->first();

            if ($tuition) {
                $payments = Payment::where('tuition_fee_id', $tuition->id)
                    ->latest('payment_date')
                    ->get();

                $quarterAmount = $tuition->total_amount / 4;
            }
        }

        return view('StudentDashboard.assessment', compact(
            'student',
            'tuition',
            'payments',
            'quarterAmount'
        ));
    }
}
