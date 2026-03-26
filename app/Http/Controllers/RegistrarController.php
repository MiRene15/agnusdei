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
use App\Support\TuitionPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
                    ->orWhere('lrn', 'like', "%{$search}%")
                    ->orWhere('shs_track', 'like', "%{$search}%");
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
        $admission = Admission::with('requirements')->findOrFail($id);

        if (strtolower((string) $admission->status) === 'approved') {
            return back()->with('error', 'Approved admission can no longer be modified.');
        }

        if (!$admission->lrn || !$admission->birth_date) {
            return back()->with('error', 'Student identity details are incomplete. LRN and birth date are required before verification.');
        }

        if (TuitionPlanner::requiresShsTrack($admission->applying_for_grade) && !TuitionPlanner::normalizeTrack($admission->shs_track)) {
            return back()->with('error', 'Senior High applicants must have a track selected before verification.');
        }

        if (!$admission->requirements()->where('submitted', 1)->exists()) {
            return back()->with('error', 'Please review at least one submitted requirement before verifying this admission.');
        }

        DB::transaction(function () use ($admission) {
            $institutionalEmail = $admission->institutional_email
                ?: $this->generateInstitutionalEmail($admission->first_name, $admission->last_name);

            $admission->update([
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => Auth::id(),
                'institutional_email' => $institutionalEmail,
                'status' => 'under_review',
                'remarks' => 'Verified by registrar and endorsed for cashier payment',
            ]);

            $student = $this->upsertStudentFromAdmission($admission, 'verified');
            $this->syncTuitionRecord($student);
        });

        ActivityLog::record(Auth::id(), 'admission', 'verify', 'Admission', $admission->id, 'Registrar verified admission #' . $admission->application_number, request()->ip());

        return back()->with('success', 'Admission verified successfully. Student billing is now ready for cashier payment.');
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

        ActivityLog::record(Auth::id(), 'admission', 'mark_incomplete', 'Admission', $admission->id, 'Marked admission as incomplete', request()->ip());

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

        ActivityLog::record(Auth::id(), 'admission', 'batch_incomplete', 'Admission', null, "Batch marked incomplete count: {$updated}", request()->ip());

        return back()->with('success', 'Selected admissions were marked incomplete.');
    }

    public function batchApprove(Request $request)
    {
        $request->validate([
            'admission_ids' => 'required|array',
            'admission_ids.*' => 'exists:admissions,id',
        ]);

        $approved = 0;
        $skipped = 0;
        $admissions = Admission::with('requirements')->whereIn('id', $request->admission_ids)->get();

        foreach ($admissions as $admission) {
            if (strtolower((string) $admission->status) === 'approved' || !$admission->is_verified || $admission->requirements()->where('submitted', 0)->exists()) {
                $skipped++;
                continue;
            }

            DB::transaction(function () use ($admission, &$approved) {
                $this->approveAdmissionOnly($admission);
                ActivityLog::record(Auth::id(), 'admission', 'batch_approve', 'Admission', $admission->id, 'Batch approved admission #' . $admission->application_number, request()->ip());
                $approved++;
            });
        }

        if ($approved === 0) {
            return back()->with('error', 'No selected admissions were eligible for approval.');
        }

        $message = "{$approved} admission(s) approved successfully.";
        if ($skipped > 0) {
            $message .= " {$skipped} skipped because they were already approved, unverified, or incomplete.";
        }

        return back()->with('success', $message);
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
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('shs_track', 'like', "%{$search}%");
            });
        }

        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->grade_level);
        }

        if ($request->filled('section')) {
            $query->where('section', $request->section);
        }

        $students = $query->latest()->paginate(10)->withQueryString();
        $sections = Section::where('is_active', true)->orderBy('grade_level')->orderBy('section_name')->get()->groupBy('grade_level');

        return view('RegistrarDashboard.students', compact('students', 'sections'));
    }

    public function sectioning(Request $request)
    {
        $schoolYear = $request->get('school_year', TuitionPlanner::currentSchoolYear());

        $students = Student::whereIn('status', ['verified', 'payment_cleared', 'approved', 'enrolled'])
            ->where('is_transferred', false)
            ->with(['tuitionFees' => fn ($query) => $query->where('school_year', $schoolYear)])
            ->orderBy('grade_level')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $sections = Section::where('is_active', true)->orderBy('grade_level')->orderBy('section_name')->get()->groupBy('grade_level');
        $sectionCounts = Student::selectRaw('grade_level, section, school_year, COUNT(*) as total')
            ->whereNotNull('section')
            ->groupBy('grade_level', 'section', 'school_year')
            ->get()
            ->keyBy(fn ($item) => $item->grade_level . '|' . $item->section . '|' . $item->school_year);
        $alignedClassCounts = Classes::with(['subject', 'teacher'])
            ->where('school_year', $schoolYear)
            ->get()
            ->groupBy(fn ($class) => $class->grade_level . '|' . $class->section)
            ->map(fn ($classes) => $classes->filter(fn ($class) => $class->teacher && $class->subject && trim((string) $class->subject->grade_level) === trim((string) $class->grade_level))->count());

        return view('RegistrarDashboard.section', compact('students', 'sections', 'sectionCounts', 'alignedClassCounts', 'schoolYear'));
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

        if (!$this->sectionMatchesStudentTrack($student, $request->section)) {
            return back()->with('error', 'The selected Senior High section does not match the student track.');
        }

        $sectionRecord = Section::where('grade_level', $student->grade_level)
            ->where('section_name', $request->section)
            ->where('is_active', true)
            ->first();

        if (!$sectionRecord) {
            return back()->with('error', 'Selected section is invalid for this grade level.');
        }

        $currentSectionCount = Student::where('grade_level', $student->grade_level)
            ->where('section', $request->section)
            ->where('school_year', $request->school_year)
            ->where('id', '!=', $student->id)
            ->count();

        if ($currentSectionCount >= $sectionRecord->capacity) {
            return back()->with('error', 'Selected section is already full.');
        }

        if (!$this->studentCanBeEnrolled($student, $request->school_year)) {
            return back()->with('error', 'Student must settle the required down payment before enrollment.');
        }

        $alignedClasses = $this->getAlignedClasses($student, $request->section, $request->school_year);

        if ($alignedClasses->isEmpty()) {
            return back()->with('error', 'No aligned subject-teacher class records were found for the selected section and school year.');
        }

        $previousSection = $student->section;
        $previousSchoolYear = $student->school_year;

        DB::transaction(function () use ($student, $request, $previousSection, $previousSchoolYear, $alignedClasses) {
            $student->update([
                'section' => $request->section,
                'school_year' => $request->school_year,
                'status' => 'enrolled',
            ]);

            $this->syncStudentClassEnrollments($student, $alignedClasses->pluck('id')->all(), $request->school_year, $previousSection, $previousSchoolYear);
        });

        ActivityLog::record(Auth::id(), 'enrollment', 'assign_section', 'Student', $student->id, 'Assigned section ' . $request->section . ' for ' . $request->school_year, request()->ip());

        return back()->with('success', 'Student successfully enrolled and assigned to section.');
    }

    public function autoAssignSection($id)
    {
        if (!AcademicEvent::enabled('enrollment_open')) {
            return back()->with('error', 'Enrollment is currently closed by admin.');
        }

        $student = Student::findOrFail($id);
        $schoolYear = $student->school_year ?: TuitionPlanner::currentSchoolYear();

        if ($student->is_transferred) {
            return back()->with('error', 'Transferred students cannot be enrolled.');
        }

        if (!$this->studentCanBeEnrolled($student, $schoolYear)) {
            return back()->with('error', 'Student must settle the required down payment before enrollment.');
        }

        $sections = $this->eligibleSectionsForStudent($student);
        if ($sections->isEmpty()) {
            return back()->with('error', 'No active sections are available for this student.');
        }

        $bestSection = null;
        $bestClasses = collect();
        $lowestCount = PHP_INT_MAX;

        foreach ($sections as $section) {
            $count = Student::where('grade_level', $student->grade_level)
                ->where('section', $section->section_name)
                ->where('school_year', $schoolYear)
                ->count();

            if ($count >= $section->capacity) {
                continue;
            }

            $classes = $this->getAlignedClasses($student, $section->section_name, $schoolYear);
            if ($classes->isEmpty()) {
                continue;
            }

            if ($count < $lowestCount) {
                $lowestCount = $count;
                $bestSection = $section;
                $bestClasses = $classes;
            }
        }

        if (!$bestSection) {
            return back()->with('error', 'No active section with complete subject-teacher alignment is available for this student.');
        }

        $previousSection = $student->section;
        $previousSchoolYear = $student->school_year;

        DB::transaction(function () use ($student, $bestSection, $schoolYear, $previousSection, $previousSchoolYear, $bestClasses) {
            $student->update([
                'section' => $bestSection->section_name,
                'school_year' => $schoolYear,
                'status' => 'enrolled',
            ]);

            $this->syncStudentClassEnrollments($student, $bestClasses->pluck('id')->all(), $schoolYear, $previousSection, $previousSchoolYear);
        });

        ActivityLog::record(Auth::id(), 'enrollment', 'auto_assign_section', 'Student', $student->id, 'Auto-assigned section ' . $bestSection->section_name . ' for ' . $schoolYear, request()->ip());

        return back()->with('success', 'Student auto-assigned to section ' . $bestSection->section_name . '.');
    }

    public function showStudent($id)
    {
        $student = Student::with(['admission.requirements', 'tuitionFees.payments', 'user', 'enrollments.class.subject', 'enrollments.class.teacher'])->findOrFail($id);

        return view('RegistrarDashboard.show-student', compact('student'));
    }

    public function approveCarryover(Request $request, $id, $tuitionId)
    {
        $student = Student::with('admission.requirements')->findOrFail($id);
        $tuition = TuitionFee::where('student_id', $student->id)->findOrFail($tuitionId);

        if ($tuition->school_year === ($student->school_year ?: TuitionPlanner::currentSchoolYear())) {
            return back()->with('error', 'Carryover can only be approved for a previous school year balance.');
        }

        if ((float) $tuition->balance <= 0) {
            return back()->with('error', 'There is no remaining balance to carry over.');
        }

        if (!$student->admission || $student->admission->requirements->where('submitted', 0)->isNotEmpty()) {
            return back()->with('error', 'Admission requirements must be completed before carryover can be approved.');
        }

        $tuition->update([
            'carryover_approved' => true,
            'carryover_approved_at' => now(),
            'carryover_approved_by' => Auth::id(),
        ]);

        $this->syncTuitionRecord($student);

        ActivityLog::record(Auth::id(), 'tuition', 'approve_carryover', 'TuitionFee', $tuition->id, 'Approved carryover for tuition record #' . $tuition->id, $request->ip());

        return back()->with('success', 'Previous balance approved for carryover to the next school year.');
    }

    public function confirmShsVoucher(Request $request, $id, $tuitionId)
    {
        $student = Student::with('admission.requirements')->findOrFail($id);
        $tuition = TuitionFee::where('student_id', $student->id)->findOrFail($tuitionId);

        if (!TuitionPlanner::requiresShsTrack($student->grade_level)) {
            return back()->with('error', 'SHS voucher confirmation only applies to Senior High students.');
        }

        if (!$student->admission || !$student->admission->requirements()->where('requirement_name', 'SHS Voucher')->where('submitted', 1)->exists()) {
            return back()->with('error', 'The SHS voucher document must be submitted before registrar confirmation.');
        }

        $tuition->update([
            'voucher_status' => $tuition->voucher_status === 'cashier_verified' ? 'verified' : 'registrar_verified',
            'voucher_registrar_verified_at' => now(),
            'voucher_registrar_verified_by' => Auth::id(),
        ]);

        ActivityLog::record(Auth::id(), 'tuition', 'confirm_shs_voucher', 'TuitionFee', $tuition->id, 'Registrar confirmed SHS voucher for tuition record #' . $tuition->id, $request->ip());

        return back()->with('success', 'SHS voucher confirmed by registrar. Cashier confirmation can now complete the credit.');
    }

    public function markTransferred(Request $request, $id)
    {
        $request->validate(['transfer_notes' => 'required|string|max:1000']);
        $student = Student::findOrFail($id);

        $student->update([
            'is_transferred' => true,
            'transferred_at' => now(),
            'transfer_notes' => $request->transfer_notes,
            'portal_access_status' => 'locked',
            'status' => 'transferred',
        ]);

        ActivityLog::record(Auth::id(), 'student', 'mark_transferred', 'Student', $student->id, 'Marked student as transferred.', $request->ip());

        return back()->with('success', 'Student has been marked as transferred and portal access was locked.');
    }

    public function markPtcCompleted(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update(['ptc_completed' => true, 'ptc_completed_at' => now()]);

        ActivityLog::record(Auth::id(), 'student', 'mark_ptc_completed', 'Student', $student->id, 'Marked PTC as completed.', $request->ip());

        return back()->with('success', 'PTC marked as completed.');
    }

    private function processApproval(Admission $admission)
    {
        if (strtolower((string) $admission->status) === 'approved') {
            return back()->with('error', 'Admission is already approved.');
        }

        if (!$admission->is_verified) {
            return back()->with('error', 'Admission must be verified first before approval.');
        }

        if ($admission->requirements()->where('submitted', 0)->exists()) {
            return back()->with('error', 'Cannot approve. Some requirements are still incomplete.');
        }

        DB::transaction(fn () => $this->approveAdmissionOnly($admission));

        ActivityLog::record(Auth::id(), 'admission', 'approve', 'Admission', $admission->id, 'Approved admission #' . $admission->application_number, request()->ip());

        return back()->with('success', 'Admission approved successfully.');
    }

    private function approveAdmissionOnly(Admission $admission): void
    {
        $institutionalEmail = $admission->institutional_email ?: $this->generateInstitutionalEmail($admission->first_name, $admission->last_name);

        $admission->update([
            'institutional_email' => $institutionalEmail,
            'status' => 'approved',
            'remarks' => 'Approved by registrar',
        ]);

        $student = $this->upsertStudentFromAdmission($admission, 'approved');
        $this->syncTuitionRecord($student);
    }

    private function upsertStudentFromAdmission(Admission $admission, string $status): Student
    {
        $schoolYear = TuitionPlanner::currentSchoolYear();
        $institutionalEmail = $admission->institutional_email ?: $this->generateInstitutionalEmail($admission->first_name, $admission->last_name);
        $student = Student::where('admission_id', $admission->id)->first();

        if (!$student && $admission->email) {
            $student = Student::whereRaw('LOWER(email) = ?', [strtolower($admission->email)])
                ->where(function ($query) {
                    $query->whereNull('admission_id')
                        ->orWhere('admission_id', 0);
                })
                ->first();
        }

        if (!$student && $admission->lrn) {
            $student = Student::where('lrn', $admission->lrn)->first();
        }

        $student = $student ?: new Student();

        $student->fill([
            'admission_id' => $admission->id,
            'student_number' => $student->student_number ?: $this->generateStudentNumber(),
            'lrn' => $admission->lrn,
            'first_name' => $admission->first_name,
            'last_name' => $admission->last_name,
            'birth_date' => $admission->birth_date,
            'gender' => $admission->sex,
            'email' => $institutionalEmail,
            'phone' => $admission->phone,
            'address' => $admission->address,
            'grade_level' => $admission->applying_for_grade,
            'shs_track' => TuitionPlanner::normalizeTrack($admission->shs_track),
            'previous_school_type' => $admission->previous_school_type,
            'honor_rank' => $admission->honor_rank,
            'school_year' => $schoolYear,
            'status' => $status,
            'portal_access_status' => $student->portal_access_status ?: 'locked',
        ]);

        $student->save();

        return $student->fresh();
    }

    private function syncTuitionRecord(Student $student): TuitionFee
    {
        $schoolYear = $student->school_year ?: TuitionPlanner::currentSchoolYear();
        $existingTuition = TuitionFee::where('student_id', $student->id)
            ->where('school_year', $schoolYear)
            ->first();
        $existingPaidAmount = (float) ($existingTuition?->paid_amount ?? 0);
        $paymentPlan = $existingTuition?->payment_plan ?: 'monthly';

        $payload = TuitionPlanner::billingPayload($student, $schoolYear, $existingPaidAmount, $paymentPlan);

        $tuition = TuitionFee::updateOrCreate([
            'student_id' => $student->id,
            'school_year' => $schoolYear,
        ], TuitionPlanner::persistableTuitionPayload($payload));

        if (\Illuminate\Support\Facades\Schema::hasColumn('tuition_fees', 'carryover_approved')
            && \Illuminate\Support\Facades\Schema::hasColumn('tuition_fees', 'carried_over_to_school_year')) {
            TuitionFee::where('student_id', $student->id)
                ->where('school_year', '!=', $schoolYear)
                ->where('balance', '>', 0)
                ->where('carryover_approved', true)
                ->update(['carried_over_to_school_year' => $schoolYear]);
        }

        return $tuition;
    }

    private function studentCanBeEnrolled(Student $student, string $schoolYear): bool
    {
        $tuition = TuitionFee::where('student_id', $student->id)->where('school_year', $schoolYear)->first();

        return $tuition && (
            $tuition->is_downpayment_cleared
            || (float) $tuition->paid_amount >= (float) $tuition->down_payment_required
            || $tuition->voucher_status === 'verified'
        );
    }

    private function getAlignedClasses(Student $student, string $section, string $schoolYear)
    {
        return Classes::with(['subject', 'teacher'])
            ->where('grade_level', $student->grade_level)
            ->where('section', $section)
            ->where('school_year', $schoolYear)
            ->get()
            ->filter(function ($class) use ($student) {
                $subject = $class->subject;
                return $class->teacher_id
                    && $class->teacher
                    && $subject
                    && trim((string) $subject->grade_level) === trim((string) $student->grade_level);
            })
            ->values();
    }

    private function eligibleSectionsForStudent(Student $student)
    {
        $query = Section::where('grade_level', $student->grade_level)->where('is_active', true);

        if (TuitionPlanner::requiresShsTrack($student->grade_level) && $student->shs_track) {
            $query->where('section_name', 'like', TuitionPlanner::normalizeTrack($student->shs_track) . '%');
        }

        return $query->get();
    }

    private function sectionMatchesStudentTrack(Student $student, string $section): bool
    {
        if (!TuitionPlanner::requiresShsTrack($student->grade_level) || !$student->shs_track) {
            return true;
        }

        return str_starts_with(strtoupper($section), strtoupper($student->shs_track));
    }

    private function syncStudentClassEnrollments(Student $student, array $currentClassIds, string $schoolYear, ?string $previousSection = null, ?string $previousSchoolYear = null): void
    {
        if ($previousSection && $previousSchoolYear) {
            $oldClassIds = Classes::where('grade_level', $student->grade_level)
                ->where('section', $previousSection)
                ->where('school_year', $previousSchoolYear)
                ->pluck('id');

            if ($oldClassIds->isNotEmpty()) {
                Enrollment::where('student_id', $student->id)->whereIn('class_id', $oldClassIds)->delete();
            }
        }

        Enrollment::where('student_id', $student->id)
            ->whereHas('class', fn ($query) => $query->where('school_year', $schoolYear))
            ->whereNotIn('class_id', $currentClassIds)
            ->delete();

        foreach ($currentClassIds as $classId) {
            Enrollment::firstOrCreate([
                'student_id' => $student->id,
                'class_id' => $classId,
            ], [
                'enrollment_date' => now(),
                'status' => 'enrolled',
            ]);
        }
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
        $base = $base === '' ? 'student' : $base;
        $email = $base . '@agnusdei.local';
        $counter = 1;

        while (Admission::where('institutional_email', $email)->exists() || Student::where('email', $email)->exists()) {
            $email = $base . $counter . '@agnusdei.local';
            $counter++;
        }

        return $email;
    }
}
