<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $teacher = Teacher::where('user_id', $user->id)->first();

        $totalClasses = 0;
        $totalStudents = 0;

        if ($teacher) {
            $classes = Classes::where('teacher_id', $teacher->id)->get();

            $totalClasses = $classes->count();

            $totalStudents = $classes->sum(function ($class) {
                return $class->enrollments()->count();
            });
        }

        return view('TeacherDashboard.dashboard', compact(
            'teacher',
            'totalClasses',
            'totalStudents'
        ));
    }

    public function classes()
    {
        $user = Auth::user();

        $teacher = Teacher::where('user_id', $user->id)->first();

        $classes = collect();

        if ($teacher) {
            $classes = Classes::with('subject')
                ->where('teacher_id', $teacher->id)
                ->get();
        }

        return view('TeacherDashboard.classes', compact('teacher', 'classes'));
    }

    public function grades()
    {
        $user = Auth::user();

        $teacher = Teacher::where('user_id', $user->id)->first();

        $grades = collect();

        if ($teacher) {
            $classes = Classes::where('teacher_id', $teacher->id)->pluck('id');

            $grades = Grade::with('enrollment.student')
                ->whereIn('class_id', $classes)
                ->get();
        }

        return view('TeacherDashboard.grades', compact('teacher', 'grades'));
    }

    public function reports()
    {
        $user = Auth::user();

        $teacher = Teacher::where('user_id', $user->id)->first();

        return view('TeacherDashboard.reports', compact('teacher'));
    }
}