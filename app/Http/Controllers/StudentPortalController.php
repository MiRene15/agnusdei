<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Admission;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Payment;
use App\Models\Student;
use App\Models\TuitionFee;
use App\Support\TuitionPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StudentPortalController extends Controller
{
    public function check()
    {
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        if (!$student->admission_id) {
            return redirect()->route('student.admission.create');
        }

        $admission = Admission::with('requirements')->find($student->admission_id);
        if (!$admission) {
            return redirect()->route('student.admission.create');
        }

        if ($admission->requirements()->where('submitted', 0)->exists()) {
            return redirect()->route('student.requirements');
        }

        if (in_array($admission->status, ['pending', 'incomplete'], true)) {
            return redirect()->route('student.requirements');
        }

        if ($admission->is_verified || in_array($admission->status, ['approved', 'under_review'], true)) {
            $tuition = $this->currentTuition($student);

            if (!$tuition || !$tuition->is_downpayment_cleared || $student->portal_access_status !== 'unlocked') {
                return redirect()->route('student.assessment')->with('error', 'Your admission is verified. Please settle the required payment with the cashier to unlock the dashboard.');
            }

            return redirect()->route('student.dashboard');
        }

        return redirect()->route('student.requirements');
    }

    public function createAdmission()
    {
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        if ($student->admission_id) {
            $admission = Admission::find($student->admission_id);
            if ($admission && ($admission->is_verified || $admission->status === 'approved')) {
                return redirect()->route('student.portal.check')->with('error', 'Your admission has already been submitted and is locked for review.');
            }

            return redirect()->route('student.requirements')->with('error', 'You already have an admission application.');
        }

        return view('StudentDashboard.admission-create', [
            'shsTracks' => TuitionPlanner::shsTracks(),
        ]);
    }

    public function storeAdmission(Request $request)
    {
        $request->merge([
            'phone' => $this->normalizePhoneNumber($request->input('phone')),
            'lrn' => $this->normalizeDigits($request->input('lrn')),
        ]);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'sex' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'regex:/^(09\d{9}|639\d{9})$/'],
            'address' => 'nullable|string',
            'applying_for_grade' => 'required|string|max:50',
            'shs_track' => 'nullable|in:' . implode(',', TuitionPlanner::shsTracks()),
            'lrn' => ['required', 'regex:/^4\d{11}$/', 'unique:admissions,lrn', 'unique:students,lrn'],
            'previous_school' => 'nullable|string|max:255',
            'previous_school_type' => 'nullable|in:public,private',
            'honor_rank' => 'nullable|in:1,2,3',
        ], [
            'birth_date.required' => 'Birth date is required.',
            'birth_date.before' => 'Birth date must be earlier than today.',
            'sex.required' => 'Gender is required.',
            'lrn.required' => 'LRN is required.',
            'lrn.regex' => 'LRN must be exactly 12 digits, numbers only, and start with 4.',
            'phone.regex' => 'Phone number must be in 09XXXXXXXXX or 639XXXXXXXXX format using numbers only.',
        ]);

        if (TuitionPlanner::requiresShsTrack($request->applying_for_grade) && !TuitionPlanner::normalizeTrack($request->shs_track)) {
            return back()->withErrors([
                'shs_track' => 'Please select the Senior High track for Grade 11 or Grade 12 applicants.',
            ])->withInput();
        }

        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        if ($student->admission_id) {
            return redirect()->route('student.requirements')->with('error', 'You already have an admission application.');
        }

        $normalizedTrack = TuitionPlanner::normalizeTrack($request->shs_track);

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
            'shs_track' => $normalizedTrack,
            'previous_school' => $request->previous_school,
            'previous_school_type' => $request->previous_school_type,
            'honor_rank' => $request->honor_rank,
            'status' => 'pending',
            'application_date' => now(),
            'remarks' => null,
        ]);

        $requirements = [
            'Birth Certificate',
            'Report Card',
            'Good Moral Certificate',
            '2x2 ID Picture',
        ];

        if (TuitionPlanner::requiresShsTrack($request->applying_for_grade)) {
            $requirements[] = 'SHS Voucher';
        }

        foreach ($requirements as $requirement) {
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
            'shs_track' => $normalizedTrack,
            'previous_school_type' => $request->previous_school_type,
            'honor_rank' => $request->honor_rank,
            'status' => 'pending',
            'portal_access_status' => 'locked',
            'school_year' => TuitionPlanner::currentSchoolYear(),
        ]);

        ActivityLog::record(Auth::id(), 'admission', 'submit', 'Admission', $admission->id, 'Student submitted admission application #' . $admission->application_number, $request->ip());

        return redirect()->route('student.requirements')->with('success', 'Admission application submitted successfully.');
    }

    public function requirements()
    {
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        if (!$student->admission_id) {
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
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        if (!$student->admission_id) {
            return redirect()->route('student.admission.create')->with('error', 'Admission record not found.');
        }

        $admission = Admission::find($student->admission_id);
        if (!$admission) {
            return redirect()->route('student.admission.create')->with('error', 'Admission record not found.');
        }

        if ($admission->is_verified || $admission->status === 'approved') {
            return back()->with('error', 'Admission is already verified and locked.');
        }

        if ($request->hasFile('documents')) {
            $request->validate([
                'documents' => 'required|array',
                'documents.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            ]);

            $uploadedCount = 0;

            foreach ((array) $request->file('documents') as $requirementId => $document) {
                if (!$document) {
                    continue;
                }

                $requirement = $admission->requirements()->where('id', $requirementId)->first();
                if (!$requirement) {
                    continue;
                }

                $path = $document->store('requirements', 'public');
                $requirement->update([
                    'submitted' => 1,
                    'submitted_at' => now(),
                    'status' => 'submitted',
                    'file_path' => $path,
                ]);

                ActivityLog::record(Auth::id(), 'admission_requirement', 'upload', 'AdmissionRequirement', $requirement->id, 'Uploaded requirement ' . $requirement->requirement_name . '.', $request->ip());
                $uploadedCount++;
            }

            if ($uploadedCount === 0) {
                return back()->with('error', 'Please choose at least one file to upload.');
            }

            return back()->with('success', $uploadedCount . ' requirement file(s) uploaded successfully.');
        }

        $request->validate([
            'requirement_id' => 'required|exists:admission_requirements,id',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
        ]);

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

        ActivityLog::record(Auth::id(), 'admission_requirement', 'upload', 'AdmissionRequirement', $requirement->id, 'Uploaded requirement ' . $requirement->requirement_name . '.', $request->ip());

        return back()->with('success', 'Requirement uploaded successfully.');
    }

    public function enrollment()
    {
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        return view('StudentDashboard.enrollments', compact('student'));
    }

    public function dashboard()
    {
        $student = $this->requireUnlockedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $enrollments = Enrollment::with(['class.subject', 'class.schedules'])->where('student_id', $student->id)->get();
        $grades = Grade::with('enrollment.class.subject')->whereIn('enrollment_id', $enrollments->pluck('id'))->get();
        $tuition = $this->currentTuition($student);
        $payments = $tuition ? Payment::where('tuition_fee_id', $tuition->id)->latest('payment_date')->get() : collect();

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

        return view('StudentDashboard.dashboard', compact('student', 'enrollments', 'grades', 'tuition', 'payments', 'schedule'));
    }

    public function subjects()
    {
        $student = $this->requireUnlockedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $enrollments = Enrollment::with('class.subject')->where('student_id', $student->id)->get();

        return view('StudentDashboard.subjects', compact('student', 'enrollments'));
    }

    public function grades(Request $request)
    {
        $student = $this->requireUnlockedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $enrollmentIds = Enrollment::where('student_id', $student->id)->pluck('id');
        $periodOptions = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
        $selectedPeriod = trim((string) $request->query('period'));

        $gradesQuery = Grade::with('enrollment.class.subject')
            ->whereIn('enrollment_id', $enrollmentIds);

        if ($selectedPeriod !== '' && in_array($selectedPeriod, $periodOptions, true)) {
            $gradesQuery->where('grading_period', $selectedPeriod);
        } else {
            $selectedPeriod = '';
        }

        $grades = $gradesQuery
            ->orderBy('grading_period')
            ->get()
            ->sortBy(fn ($grade) => strtolower((string) ($grade->enrollment->class->subject->subject_name ?? '')))
            ->values();

        return view('StudentDashboard.grades', compact('student', 'grades', 'periodOptions', 'selectedPeriod'));
    }

    public function scheduleView()
    {
        $student = $this->requireUnlockedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $enrollments = Enrollment::with(['class.subject', 'class.schedules'])->where('student_id', $student->id)->get();
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

        return view('StudentDashboard.schedule', compact('student', 'schedule'));
    }

    public function assessment()
    {
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $tuition = $this->currentTuition($student);
        $payments = $tuition ? Payment::where('tuition_fee_id', $tuition->id)->latest('payment_date')->get() : collect();
        $quarterAmount = $tuition ? round(((float) $tuition->total_due) / 4, 2) : 0;
        $remainingForUnlock = $tuition ? max(0, (float) $tuition->down_payment_required - (float) $tuition->paid_amount) : 0;

        return view('StudentDashboard.assessment', compact('student', 'tuition', 'payments', 'quarterAmount', 'remainingForUnlock'));
    }

    private function authenticatedStudent()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $student = Student::where('user_id', $user->id)->first();
        if (!$student) {
            return redirect()->route('login')->withErrors(['email' => 'Student profile not found.']);
        }

        if ($student->is_transferred) {
            return redirect()->route('login')->withErrors(['email' => 'This student account has been marked as transferred and is no longer active.']);
        }

        if ($student->status === 'withdrawn') {
            return redirect()->route('login')->withErrors(['email' => 'This student account has been marked as withdrawn and is no longer active for the current school year.']);
        }

        return $student;
    }

    private function requireUnlockedStudent()
    {
        $student = $this->authenticatedStudent();
        if ($student instanceof \Illuminate\Http\RedirectResponse) {
            return $student;
        }

        $admission = $student->admission_id ? Admission::find($student->admission_id) : null;
        if (!$admission || (!$admission->is_verified && $admission->status !== 'approved')) {
            return redirect()->route('student.portal.check')->with('error', 'Your account is not yet verified.');
        }

        $tuition = $this->currentTuition($student);
        if (!$tuition || !$tuition->is_downpayment_cleared || $student->portal_access_status !== 'unlocked') {
            return redirect()->route('student.assessment')->with('error', 'Please settle the required payment with the cashier first before accessing the dashboard.');
        }

        return $student;
    }

    private function currentTuition(Student $student)
    {
        $schoolYear = $student->school_year ?: TuitionPlanner::currentSchoolYear();

        return TuitionFee::where('student_id', $student->id)
            ->where('school_year', $schoolYear)
            ->first();
    }

    private function normalizePhoneNumber(?string $value): ?string
    {
        $digits = $this->normalizeDigits($value);

        return $digits !== '' ? $digits : null;
    }

    private function normalizeDigits(?string $value): string
    {
        return preg_replace('/\D+/', '', (string) $value);
    }
}
