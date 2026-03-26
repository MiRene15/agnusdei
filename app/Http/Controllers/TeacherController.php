<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use App\Models\ActivityLog;
use App\Models\Classes;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();
        $totalClasses = 0;
        $totalStudents = 0;
        $upcomingSchedules = collect();

        if ($teacher) {
            $classes = Classes::with(['subject', 'schedules', 'enrollments'])
                ->where('teacher_id', $teacher->id)
                ->orderBy('grade_level')
                ->orderBy('section')
                ->get();

            $totalClasses = $classes->count();
            $totalStudents = $classes->sum(fn ($class) => $class->enrollments->count());

            $upcomingSchedules = $classes->flatMap(function ($class) {
                return $class->schedules->map(function ($schedule) use ($class) {
                    return [
                        'subject_name' => $class->subject->subject_name ?? '-',
                        'section' => $class->section,
                        'grade_level' => $class->grade_level,
                        'day_of_week' => $schedule->day_of_week,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'room' => $schedule->room,
                    ];
                });
            });
        }

        return view('TeacherDashboard.dashboard', compact(
            'teacher',
            'classes',
            'totalClasses',
            'totalStudents',
            'upcomingSchedules'
        ));
    }

    public function classes()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();

        if ($teacher) {
            $classes = Classes::with(['subject', 'schedules', 'enrollments.student'])
                ->where('teacher_id', $teacher->id)
                ->orderBy('grade_level')
                ->orderBy('section')
                ->get();
        }

        return view('TeacherDashboard.classes', compact('teacher', 'classes'));
    }

    public function schedule()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();
        $schedules = collect();

        if ($teacher) {
            $classes = Classes::with(['subject', 'schedules'])
                ->where('teacher_id', $teacher->id)
                ->orderBy('grade_level')
                ->orderBy('section')
                ->get();

            $schedules = $classes->flatMap(function ($class) {
                return $class->schedules->map(function ($schedule) use ($class) {
                    return [
                        'subject_name' => $class->subject->subject_name ?? '-',
                        'subject_code' => $class->subject->subject_code ?? '-',
                        'is_advisory' => $class->is_advisory,
                        'grade_level' => $class->grade_level,
                        'section' => $class->section,
                        'day_of_week' => $schedule->day_of_week,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                        'room' => $schedule->room,
                    ];
                });
            });
        }

        return view('TeacherDashboard.schedule', compact('teacher', 'schedules'));
    }

    public function grades(Request $request)
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();
        $selectedClass = null;
        $enrollments = collect();
        $studentSearch = trim((string) $request->query('student_search'));

        if ($teacher) {
            $classes = Classes::with('subject')
                ->withCount('enrollments')
                ->where('teacher_id', $teacher->id)
                ->orderBy('grade_level')
                ->orderBy('section')
                ->get();

            if ($classes->isNotEmpty()) {
                $selectedClassId = (int) $request->integer('class_id');
                $selectedClass = Classes::with(['subject', 'schedules', 'enrollments.student', 'enrollments.grades'])
                    ->where('teacher_id', $teacher->id)
                    ->where('id', $selectedClassId ?: (int) $classes->first()->id)
                    ->first();

                if (!$selectedClass) {
                    $selectedClass = Classes::with(['subject', 'schedules', 'enrollments.student', 'enrollments.grades'])
                        ->where('teacher_id', $teacher->id)
                        ->where('id', (int) $classes->first()->id)
                        ->first();
                }

                if ($selectedClass) {
                    $enrollments = $selectedClass->enrollments
                        ->sortBy(fn ($enrollment) => mb_strtolower(trim(
                            ($enrollment->student->last_name ?? '') . ' ' . ($enrollment->student->first_name ?? '')
                        )))
                        ->values();

                    if ($studentSearch !== '') {
                        $needle = mb_strtolower($studentSearch);
                        $enrollments = $enrollments->filter(function ($enrollment) use ($needle) {
                            $student = $enrollment->student;
                            $haystack = implode(' ', [
                                $student->first_name ?? '',
                                $student->last_name ?? '',
                                $student->student_number ?? '',
                                $student->lrn ?? '',
                            ]);

                            return str_contains(mb_strtolower($haystack), $needle);
                        })->values();
                    }
                }
            }
        }

        return view('TeacherDashboard.grades', compact('teacher', 'classes', 'selectedClass', 'enrollments', 'studentSearch'));
    }

    public function saveGrades(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grading_period' => 'required|string|max:50',
            'seatwork_score' => 'required|numeric|min:0|max:100',
            'quiz_score' => 'required|numeric|min:0|max:100',
            'exam_score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:255',
        ]);

        $enrollment = Enrollment::with(['student', 'class'])->findOrFail($request->enrollment_id);
        $teacher = Teacher::where('user_id', Auth::id())->first();

        if (!$teacher || (int) $enrollment->class->teacher_id !== (int) $teacher->id) {
            return back()->with('error', 'You can only encode grades for your assigned classes.');
        }

        if (!AcademicEvent::enabled('grade_encoding_open')) {
            return back()->with('error', 'Grade encoding is currently closed. Please wait until the active grading window is opened by the school.');
        }

        if (AcademicEvent::enabled('ptc_required') && !$enrollment->student?->ptc_completed) {
            return back()->with('error', 'PTC must be completed face-to-face before grades can be encoded for this student.');
        }

        $existingGrade = Grade::where('enrollment_id', $request->enrollment_id)
            ->where('grading_period', $request->grading_period)
            ->first();

        if ($existingGrade) {
            return back()->with('error', 'This grading period is already locked after upload and can no longer be edited.');
        }

        $finalGrade = $this->computeFinalGrade(
            (float) $request->seatwork_score,
            (float) $request->quiz_score,
            (float) $request->exam_score
        );

        Grade::create([
            'enrollment_id' => $request->enrollment_id,
            'grading_period' => $request->grading_period,
            'seatwork_score' => $request->seatwork_score,
            'quiz_score' => $request->quiz_score,
            'exam_score' => $request->exam_score,
            'final_grade' => $finalGrade,
            'grade' => $finalGrade,
            'remarks' => $request->remarks ?: ($finalGrade >= 75 ? 'Passed' : 'Needs Intervention'),
        ]);

        ActivityLog::record(
            Auth::id(),
            'grade',
            'encode',
            'Enrollment',
            $enrollment->id,
            'Encoded ' . $request->grading_period . ' grades for ' . ($enrollment->student->student_number ?? 'student') . '.',
            $request->ip()
        );

        return back()->with('success', 'Grade saved successfully.');
    }

    public function reports()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();
        $totalClasses = 0;
        $totalStudents = 0;
        $totalGradesEncoded = 0;

        if ($teacher) {
            $classes = Classes::with(['enrollments.grades'])
                ->where('teacher_id', $teacher->id)
                ->get();

            $totalClasses = $classes->count();
            $totalStudents = $classes->sum(fn ($class) => $class->enrollments->count());
            $totalGradesEncoded = $classes->sum(function ($class) {
                return $class->enrollments->sum(fn ($enrollment) => $enrollment->grades->count());
            });
        }

        return view('TeacherDashboard.reports', compact(
            'teacher',
            'classes',
            'totalClasses',
            'totalStudents',
            'totalGradesEncoded'
        ));
    }

    private function computeFinalGrade(float $seatworkScore, float $quizScore, float $examScore): float
    {
        return round(($seatworkScore * 0.30) + ($quizScore * 0.30) + ($examScore * 0.40), 2);
    }
}
