<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\RoleReferenceCode;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'nullable|string|max:20',
            'role' => 'required|in:student,parent,teacher,registrar,cashier',
            'reference_code' => 'nullable|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $referenceCode = null;

        if (in_array($request->role, ['teacher', 'registrar', 'cashier'])) {
            if (!$request->filled('reference_code')) {
                return back()->withErrors([
                    'reference_code' => 'Reference code is required for this role.',
                ])->withInput();
            }

            $referenceCode = RoleReferenceCode::where('code', trim($request->reference_code))
                ->where('role', $request->role)
                ->first();

            if (!$referenceCode) {
                return back()->withErrors([
                    'reference_code' => 'Invalid reference code for the selected role.',
                ])->withInput();
            }

            if (!$referenceCode->is_active) {
                return back()->withErrors([
                    'reference_code' => 'This reference code is inactive.',
                ])->withInput();
            }

            if (!$referenceCode->canStillBeUsed()) {
                return back()->withErrors([
                    'reference_code' => 'This reference code has already reached its usage limit.',
                ])->withInput();
            }
        }

        if ($request->role === 'cashier' && User::where('role', 'cashier')->count() >= 2) {
            return back()->withErrors([
                'role' => 'Only 2 cashier accounts are allowed.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'role' => $request->role,
            'reference_code_id' => $referenceCode?->id,
            'password' => Hash::make($request->password),
        ]);

        if ($referenceCode) {
            $referenceCode->increment('used_count');
        }

        $nameParts = preg_split('/\s+/', trim($request->name), 2);
        $firstName = $nameParts[0] ?? $request->name;
        $lastName = $nameParts[1] ?? '-';

        if ($request->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_number' => $this->generateStudentNumber(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'phone' => $request->contact_number,
                'grade_level' => 'Not Yet Assigned',
                'status' => 'pending',
                'portal_access_status' => 'locked',
                'school_year' => now()->year . '-' . (now()->year + 1),
            ]);
        }

        if ($request->role === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'teacher_number' => $this->generateTeacherNumber(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'phone' => $request->contact_number,
                'department' => 'General',
                'status' => 'active',
            ]);
        }

        if ($request->role === 'parent') {
            ParentModel::create([
                'user_id' => $user->id,
                'parent_number' => $this->generateParentNumber(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->email,
                'phone' => $request->contact_number,
            ]);
        }

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        $request->session()->regenerate();

        return $this->redirectByRole(Auth::user()->role);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'registrar' => redirect()->route('registrar.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'student' => redirect()->route('student.portal.check'),
            default => redirect()->route('login'),
        };
    }

    private function generateStudentNumber(): string
    {
        do {
            $studentNumber = 'STU-' . now()->year . '-' . random_int(100000, 999999);
        } while (Student::where('student_number', $studentNumber)->exists());

        return $studentNumber;
    }

    private function generateTeacherNumber(): string
    {
        do {
            $teacherNumber = 'TCH-' . now()->year . '-' . random_int(100000, 999999);
        } while (Teacher::where('teacher_number', $teacherNumber)->exists());

        return $teacherNumber;
    }

    private function generateParentNumber(): string
    {
        do {
            $parentNumber = 'PAR-' . now()->year . '-' . random_int(100000, 999999);
        } while (ParentModel::where('parent_number', $parentNumber)->exists());

        return $parentNumber;
    }
}