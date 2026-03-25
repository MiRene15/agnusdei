<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Announcement;
use App\Models\Classes;
use App\Models\Payment;
use App\Models\RoleReferenceCode;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TuitionFee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalClasses = Classes::count();
        $totalAdmissions = Admission::count();
        $totalCollected = Payment::sum('amount');
        $totalOutstanding = TuitionFee::sum('balance');

        $recentUsers = User::latest()->take(5)->get();
        $recentAnnouncements = Announcement::latest('posted_at')->take(5)->get();
        $recentCodes = RoleReferenceCode::latest()->take(5)->get();

        return view('AdminDashboard.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'totalAdmissions',
            'totalCollected',
            'totalOutstanding',
            'recentUsers',
            'recentAnnouncements',
            'recentCodes'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('contact_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10);

        return view('AdminDashboard.users', compact('users'));
    }

    public function settings()
    {
        $admin = User::findOrFail(Auth::id());

        return view('AdminDashboard.settings', compact('admin'));
    }

    public function reports()
    {
        $usersByRole = [
            'admin' => User::where('role', 'admin')->count(),
            'registrar' => User::where('role', 'registrar')->count(),
            'teacher' => User::where('role', 'teacher')->count(),
            'parent' => User::where('role', 'parent')->count(),
            'cashier' => User::where('role', 'cashier')->count(),
            'student' => User::where('role', 'student')->count(),
        ];

        $studentCount = Student::count();
        $teacherCount = Teacher::count();
        $classCount = Classes::count();
        $admissionCount = Admission::count();
        $approvedAdmissions = Admission::where('status', 'approved')->count();
        $pendingAdmissions = Admission::where('status', 'pending')->count();

        $totalCollected = Payment::sum('amount');
        $paymentCount = Payment::count();
        $totalOutstanding = TuitionFee::sum('balance');
        $billingCount = TuitionFee::count();

        $referenceCodeCount = RoleReferenceCode::count();
        $usedReferenceCodeCount = RoleReferenceCode::where('is_used', true)->count();
        $unusedReferenceCodeCount = RoleReferenceCode::where('is_used', false)
            ->where('is_active', true)
            ->count();

        return view('AdminDashboard.reports', compact(
            'usersByRole',
            'studentCount',
            'teacherCount',
            'classCount',
            'admissionCount',
            'approvedAdmissions',
            'pendingAdmissions',
            'totalCollected',
            'paymentCount',
            'totalOutstanding',
            'billingCount',
            'referenceCodeCount',
            'usedReferenceCodeCount',
            'unusedReferenceCodeCount'
        ));
    }

    public function announcements()
    {
        $announcements = Announcement::latest('posted_at')->paginate(10);

        return view('AdminDashboard.announcements', compact('announcements'));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'audience' => 'required|string|max:100',
        ]);

        Announcement::create([
            'title' => $request->title,
            'message' => $request->message,
            'audience' => $request->audience,
            'posted_at' => now(),
        ]);

        return back()->with('success', 'Announcement posted successfully.');
    }

    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        return back()->with('success', 'Announcement deleted successfully.');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $admin = User::findOrFail(Auth::id());

        $admin->name = $request->name;
        $admin->contact_number = $request->contact_number;

        if ($request->filled('password')) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return back()->with('success', 'Settings updated successfully.');
    }

    public function referenceCodes(Request $request)
    {
        $query = RoleReferenceCode::with(['subject', 'creator', 'usedBy']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'used') {
                $query->where('is_used', true);
            } elseif ($request->status === 'unused') {
                $query->where('is_used', false);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'active') {
                $query->where('is_active', true);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('section', 'like', "%{$search}%")
                    ->orWhere('grade_level', 'like', "%{$search}%")
                    ->orWhere('school_year', 'like', "%{$search}%")
                    ->orWhere('semester', 'like', "%{$search}%");
            });
        }

        $codes = $query->latest()->paginate(10);
        $subjects = Subject::orderBy('subject_name')->get();

        return view('AdminDashboard.reference-codes', compact('codes', 'subjects'));
    }

    public function storeReferenceCode(Request $request)
    {
        $request->validate([
            'role' => 'required|in:teacher,registrar,cashier,admin',
            'subject_id' => 'nullable|exists:subjects,id',
            'section' => 'nullable|string|max:50',
            'grade_level' => 'nullable|string|max:50',
            'school_year' => 'nullable|string|max:30',
            'semester' => 'nullable|string|max:30',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($request->role !== 'teacher' && $request->filled('subject_id')) {
            return back()->withErrors([
                'subject_id' => 'Only teacher reference codes can be linked to a subject.',
            ])->withInput();
        }

        do {
            $generatedCode = strtoupper(Str::random(10));
        } while (RoleReferenceCode::where('code', $generatedCode)->exists());

        RoleReferenceCode::create([
            'role' => $request->role,
            'code' => $generatedCode,
            'subject_id' => $request->role === 'teacher' ? $request->subject_id : null,
            'section' => $request->role === 'teacher' ? $request->section : null,
            'grade_level' => $request->role === 'teacher' ? $request->grade_level : null,
            'school_year' => $request->role === 'teacher' ? $request->school_year : null,
            'semester' => $request->role === 'teacher' ? $request->semester : null,
            'created_by' => Auth::id(),
            'used_by' => null,
            'is_used' => false,
            'is_active' => true,
            'expires_at' => $request->expires_at,
            'used_at' => null,
        ]);

        return back()->with('success', 'Reference code created successfully.');
    }

    public function deactivateReferenceCode($id)
    {
        $code = RoleReferenceCode::findOrFail($id);

        $code->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'Reference code deactivated successfully.');
    }
}