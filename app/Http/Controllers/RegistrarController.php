<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use App\Models\ActivityLog;
use App\Models\Admission;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Section;
use App\Models\Student;
use App\Models\TuitionFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrarController extends Controller
{
    public function dashboard()
    {
        $totalApplicants = Admission::count();
        $pendingApplicants = Admission::where('status', 'pending')->count();
        $approvedApplicants = Admission::where('status', 'approved')->count();
        $incompleteApplicants = Admission::where('status', 'incomplete')->count();

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
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('lrn', 'like', "%{$search}%");
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

    public function verifyEnrollment($id)
    {
        $admission = Admission::findOrFail($id);

        if (strtolower((string) $admission->status) === 'approved') {
            return back()->with('error', 'Approved admission can no longer be modified.');
        }

        $institutionalEmail = $this->generateInstitutionalEmail(
            $admission->first_name,
            $admission->last_name
        );

        $admission->update([
            'is_verified' => true,
            'verified_at' => now(),
            'verified_by' => Auth::id(),
            'institutional_email' => $institutionalEmail,
            'status' => 'under_review',
            'remarks' => 'Verified by registrar',
        ]);

        ActivityLog::record(
            Auth::id(),
            'admission',
            'verify',
            'Admission',
            $admission->id,
            'Registrar verified admission #' . $admission->application_number,
            request()->ip()
        );

        return back()->with('success', 'Admission verified successfully.');
    }

    public function approveEnrollment($id)
    {
        $admission = Admission::with('requirements')->findOrFail($id);

        return $this->processApproval($admission);
    }

    public function markIncomplete($id)
    {
        $admission = Admission::findOrFail($id);

        if (strtolower((string) $admission->status) === 'approved') {
            return back()->with('error', 'Approved admission can no longer be changed.');
        }

        $admission->update([
            'status' => 'incomplete',
            'remarks' => 'Marked incomplete by registrar',
        ]);

        ActivityLog::record(
            Auth::id(),
            'admission',
            'mark_incomplete',
            'Admission',
            $admission->id,
            'Marked admission as incomplete',
            request()->ip()
        );

        return back()->with('success', 'Admission marked as incomplete.');
    }

    public function batchIncomplete(Request $request)
    {
        $request->validate([
            'admission_ids' => 'required|array',
            'admission_ids.*' => 'exists:admissions,id',
        ]);

        $updated = Admission::whereIn('id', $request->admission_ids)
            ->where('status', '!=', 'approved')
            ->update([
                'status' => 'incomplete',
                'remarks' => 'Marked incomplete by registrar',
            ]);

        ActivityLog::record(
            Auth::id(),
            'admission',
            'batch_incomplete',
            'Admission',
            null,
            "Batch marked incomplete count: {$updated}",
            request()->ip()
        );

        return back()->with('success', 'Selected admissions were marked incomplete.');
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

    $students = $query->latest()->paginate(10)->withQueryString();

    $sections = Section::where('is_active', true)
        ->orderBy('grade_level')
        ->orderBy('section_name')
        ->get()
        ->groupBy('grade_level');

    return view('RegistrarDashboard.students', compact('students', 'sections'));
}

    public function sectioning(Request $request)
{
    $students = Student::whereIn('status', ['approved', 'enrolled'])
        ->where('is_transferred', false)
        ->orderBy('grade_level')
        ->orderBy('last_name')
        ->orderBy('first_name')
        ->get();

    $sections = Section::where('is_active', true)
        ->orderBy('grade_level')
        ->orderBy('section_name')
        ->get()
        ->groupBy('grade_level');

    $sectionCounts = Student::selectRaw('grade_level, section, school_year, COUNT(*) as total')
        ->whereNotNull('section')
        ->groupBy('grade_level', 'section', 'school_year')
        ->get()
        ->keyBy(function ($item) {
            return $item->grade_level . '|' . $item->section . '|' . $item->school_year;
        });

    return view('RegistrarDashboard.section', compact('students', 'sections', 'sectionCounts'));
}
    public function updateSection(Request $request, $id)
    {
        if (!AcademicEvent::enabled('enrollment_open')) {
            return back()->with('error', 'Enrollment is currently closed by admin.');
        }

        $request->validate([
            'section' => 'required|string|max:50',
            'school_year' => 'required|string|max:20',
        ]);

        $student = Student::findOrFail($id);

        if ($student->is_transferred) {
            return back()->with('error', 'Transferred students cannot be enrolled.');
        }

        $currentSectionCount = Student::where('grade_level', $student->grade_level)
            ->where('section', $request->section)
            ->where('school_year', $request->school_year)
            ->where('id', '!=', $student->id)
            ->count();

        $sectionRecord = Section::where('grade_level', $student->grade_level)
            ->where('section_name', $request->section)
            ->where('is_active', true)
            ->first();

        if (!$sectionRecord) {
            return back()->with('error', 'Selected section is invalid for this grade level.');
        }

        if ($currentSectionCount >= $sectionRecord->capacity) {
            return back()->with('error', 'Selected section is already full.');
        }
        $tuition = TuitionFee::where('student_id', $student->id)
            ->where('school_year', $request->school_year)
            ->first();

        if (!$tuition) {
            return back()->with('error', 'Billing record not found for this student.');
        }

        if (!$tuition->is_downpayment_cleared && $tuition->paid_amount < $tuition->down_payment_required) {
            return back()->with('error', 'Student must settle the required down payment before enrollment.');
        }

        $student->update([
            'section' => $request->section,
            'school_year' => $request->school_year,
            'status' => 'enrolled',
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

        ActivityLog::record(
            Auth::id(),
            'enrollment',
            'assign_section',
            'Student',
            $student->id,
            'Assigned section ' . $request->section . ' for ' . $request->school_year,
            request()->ip()
        );

        return back()->with('success', 'Student successfully enrolled and assigned to section.');
    }

    public function showStudent($id)
    {
        $student = Student::with('admission')->findOrFail($id);

        return view('RegistrarDashboard.show-student', compact('student'));
    }

    private function processApproval(Admission $admission)
    {
        if (strtolower((string) $admission->status) === 'approved') {
            return back()->with('error', 'Admission is already approved.');
        }

        if (!$admission->is_verified) {
            return back()->with('error', 'Admission must be verified first before approval.');
        }

        $hasIncompleteRequirements = $admission->requirements()->where('submitted', 0)->exists();

        if ($hasIncompleteRequirements) {
            return back()->with('error', 'Cannot approve. Some requirements are still incomplete.');
        }

        $this->approveAdmissionOnly($admission);

        ActivityLog::record(
            Auth::id(),
            'admission',
            'approve',
            'Admission',
            $admission->id,
            'Approved admission #' . $admission->application_number,
            request()->ip()
        );

        return back()->with('success', 'Admission approved successfully.');
    }

    private function approveAdmissionOnly(Admission $admission)
    {
        $schoolYear = now()->year . '-' . (now()->year + 1);

        $institutionalEmail = $admission->institutional_email
            ?: $this->generateInstitutionalEmail($admission->first_name, $admission->last_name);

        $student = Student::where('admission_id', $admission->id)->first();

        if ($student) {
            $student->update([
                'lrn' => $admission->lrn,
                'first_name' => $admission->first_name,
                'last_name' => $admission->last_name,
                'birth_date' => $admission->birth_date,
                'gender' => $admission->sex,
                'email' => $institutionalEmail,
                'phone' => $admission->phone,
                'address' => $admission->address,
                'grade_level' => $admission->applying_for_grade,
                'school_year' => $schoolYear,
                'status' => 'approved',
                'portal_access_status' => 'locked',
            ]);
        } else {
            $student = Student::create([
                'user_id' => null,
                'parent_id' => null,
                'admission_id' => $admission->id,
                'student_number' => $this->generateStudentNumber(),
                'lrn' => $admission->lrn,
                'first_name' => $admission->first_name,
                'last_name' => $admission->last_name,
                'birth_date' => $admission->birth_date,
                'gender' => $admission->sex,
                'email' => $institutionalEmail,
                'phone' => $admission->phone,
                'address' => $admission->address,
                'grade_level' => $admission->applying_for_grade,
                'section' => null,
                'school_year' => $schoolYear,
                'status' => 'approved',
                'portal_access_status' => 'locked',
            ]);
        }

        $admission->update([
            'institutional_email' => $institutionalEmail,
            'status' => 'approved',
            'remarks' => 'Approved by registrar',
        ]);

        TuitionFee::firstOrCreate(
            [
                'student_id' => $student->id,
                'school_year' => $schoolYear,
            ],
            [
                'total_amount' => 0,
                'down_payment_required' => 0,
                'monthly_payment' => 0,
                'previous_balance' => 0,
                'total_due' => 0,
                'paid_amount' => 0,
                'balance' => 0,
                'due_date' => now()->addMonth(),
                'status' => 'unpaid',
                'is_downpayment_cleared' => false,
            ]
        );
    }

    private function generateStudentNumber(): string
    {
        do {
            $studentNumber = 'STU-' . now()->year . '-' . random_int(100000, 999999);
        } while (Student::where('student_number', $studentNumber)->exists());

        return $studentNumber;
    }

    private function generateInstitutionalEmail(string $firstName, string $lastName): string
    {
        $first = strtolower(preg_replace('/[^a-z0-9]/i', '', $firstName));
        $last = strtolower(preg_replace('/[^a-z0-9]/i', '', $lastName));

        $base = trim($first . '.' . $last, '.');
        if ($base === '') {
            $base = 'student';
        }

        $email = $base . '@agnusdei.local';
        $counter = 1;

        while (
            Admission::where('institutional_email', $email)->exists() ||
            Student::where('email', $email)->exists()
        ) {
            $email = $base . $counter . '@agnusdei.local';
            $counter++;
        }

        return $email;
    }

    public function autoAssignSection($id)
{
    if (!AcademicEvent::enabled('enrollment_open')) {
        return back()->with('error', 'Enrollment is currently closed by admin.');
    }

    $student = Student::findOrFail($id);

    if ($student->is_transferred) {
        return back()->with('error', 'Transferred students cannot be enrolled.');
    }

    $schoolYear = $student->school_year ?: (now()->year . '-' . (now()->year + 1));

    $tuition = TuitionFee::where('student_id', $student->id)
        ->where('school_year', $schoolYear)
        ->first();

    if (!$tuition) {
        return back()->with('error', 'Billing record not found for this student.');
    }

    if (!$tuition->is_downpayment_cleared && $tuition->paid_amount < $tuition->down_payment_required) {
        return back()->with('error', 'Student must settle the required down payment before enrollment.');
    }

    $sections = Section::where('grade_level', $student->grade_level)
        ->where('is_active', true)
        ->get();

    if ($sections->isEmpty()) {
        return back()->with('error', 'No active sections available for this grade level.');
    }

    $bestSection = null;
    $lowestCount = PHP_INT_MAX;

    foreach ($sections as $section) {
        $count = Student::where('grade_level', $student->grade_level)
            ->where('section', $section->section_name)
            ->where('school_year', $schoolYear)
            ->count();

        if ($count < $section->capacity && $count < $lowestCount) {
            $lowestCount = $count;
            $bestSection = $section;
        }
    }

    if (!$bestSection) {
        return back()->with('error', 'All available sections for this grade level are already full.');
    }

    $student->update([
        'section' => $bestSection->section_name,
        'school_year' => $schoolYear,
        'status' => 'enrolled',
    ]);

    $classes = Classes::where('grade_level', $student->grade_level)
        ->where('section', $bestSection->section_name)
        ->where('school_year', $schoolYear)
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

    ActivityLog::record(
        Auth::id(),
        'enrollment',
        'auto_assign_section',
        'Student',
        $student->id,
        'Auto-assigned section ' . $bestSection->section_name . ' for ' . $schoolYear,
        request()->ip()
    );

    return back()->with('success', 'Student auto-assigned to section ' . $bestSection->section_name . '.');
}
    
}