<?php

namespace App\Http\Controllers;

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
                ->get();

            $totalClasses = $classes->count();
            $totalStudents = $classes->sum(function ($class) {
                return $class->enrollments->count();
            });

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
                ->get();

            $schedules = $classes->flatMap(function ($class) {
                return $class->schedules->map(function ($schedule) use ($class) {
                    return [
                        'subject_name' => $class->subject->subject_name ?? '-',
                        'subject_code' => $class->subject->subject_code ?? '-',
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

    public function grades()
    {
        $user = Auth::user();
        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();

        if ($teacher) {
            $classes = Classes::with(['subject', 'enrollments.student', 'enrollments.grades'])
                ->where('teacher_id', $teacher->id)
                ->get();
        }

        return view('TeacherDashboard.grades', compact('teacher', 'classes'));
    }

    public function saveGrades(Request $request)
    {
        $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'grading_period' => 'required|string|max:50',
            'grade' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:255',
        ]);

        Grade::updateOrCreate(
            [
                'enrollment_id' => $request->enrollment_id,
                'grading_period' => $request->grading_period,
            ],
            [
                'grade' => $request->grade,
                'remarks' => $request->remarks,
            ]
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
            $totalStudents = $classes->sum(function ($class) {
                return $class->enrollments->count();
            });

            $totalGradesEncoded = $classes->sum(function ($class) {
                return $class->enrollments->sum(function ($enrollment) {
                    return $enrollment->grades->count();
                });
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
}