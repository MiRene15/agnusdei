<?php

namespace App\Http\Controllers;

use App\Models\AcademicEvent;
use App\Models\ActivityLog;
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
        $query = User::with('referenceCode');

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
        $events = AcademicEvent::orderBy('event_name')->get();

        return view('AdminDashboard.settings', compact('admin', 'events'));
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
        $usedReferenceCodeCount = RoleReferenceCode::where('used_count', '>', 0)->count();
        $unusedReferenceCodeCount = RoleReferenceCode::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('max_uses')
                    ->orWhereColumn('used_count', '<', 'max_uses');
            })
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

        ActivityLog::record(
            Auth::id(),
            'announcement',
            'create',
            'Announcement',
            null,
            'Created announcement for ' . $request->audience . '.',
            $request->ip()
        );

        return back()->with('success', 'Announcement posted successfully.');
    }

    public function deleteAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();

        ActivityLog::record(
            Auth::id(),
            'announcement',
            'delete',
            'Announcement',
            $id,
            'Deleted announcement #' . $id,
            request()->ip()
        );

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

        ActivityLog::record(
            Auth::id(),
            'admin',
            'update_profile',
            'User',
            $admin->id,
            'Updated admin profile settings.',
            $request->ip()
        );

        return back()->with('success', 'Settings updated successfully.');
    }

    public function referenceCodes(Request $request)
    {
        $query = RoleReferenceCode::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'used') {
                $query->where('used_count', '>', 0);
            } elseif ($request->status === 'unused') {
                $query->where(function ($q) {
                    $q->where('used_count', 0)
                        ->orWhereColumn('used_count', '<', 'max_uses');
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('role', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
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
            'description' => 'nullable|string|max:255',
            'max_uses' => 'nullable|integer|min:1',
        ]);

        do {
            $generatedCode = strtoupper($request->role) . '-' . now()->year . '-' . random_int(100, 999);
        } while (RoleReferenceCode::where('code', $generatedCode)->exists());

        RoleReferenceCode::create([
            'role' => $request->role,
            'code' => $generatedCode,
            'description' => $request->description,
            'is_active' => true,
            'max_uses' => $request->max_uses,
            'used_count' => 0,
        ]);

        ActivityLog::record(
            Auth::id(),
            'reference_code',
            'create',
            'RoleReferenceCode',
            null,
            'Created reference code for ' . $request->role . '.',
            $request->ip()
        );

        return back()->with('success', 'Reference code created successfully.');
    }

    public function deactivateReferenceCode($id)
    {
        $code = RoleReferenceCode::findOrFail($id);

        $code->update([
            'is_active' => false,
        ]);

        ActivityLog::record(
            Auth::id(),
            'reference_code',
            'deactivate',
            'RoleReferenceCode',
            $code->id,
            'Deactivated reference code ' . $code->code . '.',
            request()->ip()
        );

        return back()->with('success', 'Reference code deactivated successfully.');
    }

    public function toggleAcademicEvent(Request $request, $id)
    {
        $event = AcademicEvent::findOrFail($id);

        $event->update([
            'is_enabled' => !$event->is_enabled,
        ]);

        ActivityLog::record(
            Auth::id(),
            'academic_event',
            'toggle',
            'AcademicEvent',
            $event->id,
            'Set ' . $event->event_name . ' to ' . ($event->is_enabled ? 'enabled' : 'disabled') . '.',
            $request->ip()
        );

        return back()->with('success', $event->event_name . ' is now ' . ($event->is_enabled ? 'enabled' : 'disabled') . '.');
    }
}
