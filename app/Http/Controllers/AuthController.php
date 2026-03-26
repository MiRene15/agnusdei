<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
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

    public function showStaffRegister()
    {
        return view('auth.staff-register');
    }

    public function registerUser(Request $request)
    {
        $request->merge([
            'email' => $this->normalizeInstitutionalEmail($request->input('email_local')),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'email_local' => 'required|string|max:255|regex:/^[A-Za-z0-9._-]+$/',
            'contact_number' => ['nullable', 'regex:/^(09\d{9}|\+639\d{9})$/'],
            'role' => 'required|in:student,parent',
            'reference_code' => 'nullable|string|max:255',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=(?:.*\d){2,})(?=.*[^A-Za-z0-9]).{8,}$/'],
        ], [
            'contact_number.regex' => 'Contact number must be in 09XXXXXXXXX or +639XXXXXXXXX format.',
            'password.regex' => 'Password must have at least 8 characters, 1 uppercase letter, 1 lowercase letter, 2 numbers, and 1 special character.',
            'email_local.regex' => 'Institutional email may only use letters, numbers, periods, underscores, and hyphens.',
        ]);

        if ($request->role === 'student') {
            if (!preg_match('/@agnusdei\.local$/i', trim((string) $request->email))) {
                return back()->withErrors([
                    'email' => 'Student email must end with @agnusdei.local.',
                ])->withInput();
            }

            $referenceCode = RoleReferenceCode::where('code', trim((string) $request->reference_code))
                ->where('role', 'student')
                ->first();

            if (!$referenceCode || !$referenceCode->is_active || !$referenceCode->canStillBeUsed()) {
                return back()->withErrors([
                    'reference_code' => 'A valid active student reference code is required for student registration.',
                ])->withInput();
            }
        } else {
            $referenceCode = null;
        }

        $user = $this->createUserFromRegistration($request, $referenceCode);

        ActivityLog::record(
            $user->id,
            'auth',
            'register',
            'User',
            $user->id,
            'Public registration completed for ' . $user->role . ' account.',
            $request->ip()
        );

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    public function registerStaff(Request $request)
    {
        $request->merge([
            'email' => $this->normalizeInstitutionalEmail($request->input('email_local')),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'email_local' => 'required|string|max:255|regex:/^[A-Za-z0-9._-]+$/',
            'contact_number' => ['nullable', 'regex:/^(09\d{9}|\+639\d{9})$/'],
            'role' => 'required|in:teacher,registrar,cashier',
            'reference_code' => 'required|string|max:255',
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=(?:.*\d){2,})(?=.*[^A-Za-z0-9]).{8,}$/'],
        ], [
            'contact_number.regex' => 'Contact number must be in 09XXXXXXXXX or +639XXXXXXXXX format.',
            'password.regex' => 'Password must have at least 8 characters, 1 uppercase letter, 1 lowercase letter, 2 numbers, and 1 special character.',
            'email_local.regex' => 'Institutional email may only use letters, numbers, periods, underscores, and hyphens.',
        ]);

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

        if ($request->role === 'cashier' && User::where('role', 'cashier')->count() >= 2) {
            return back()->withErrors([
                'role' => 'Only 2 cashier accounts are allowed.',
            ])->withInput();
        }

        $user = $this->createUserFromRegistration($request, $referenceCode);

        ActivityLog::record(
            $user->id,
            'auth',
            'staff_register',
            'User',
            $user->id,
            'Staff registration completed for ' . $user->role . ' account.',
            $request->ip()
        );

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    public function loginUser(Request $request)
    {
        $request->validate([
            'email_local' => 'required|string|max:255|regex:/^[A-Za-z0-9._@-]+$/',
            'password' => 'required|string',
        ], [
            'email_local.regex' => 'Institutional email may only use letters, numbers, periods, underscores, hyphens, and the @ symbol.',
        ]);

        $loginEmail = $this->normalizeInstitutionalLogin($request->input('email_local'));

        if ($loginEmail === '') {
            return back()->withErrors([
                'email_local' => 'Please enter your institutional email username.',
            ])->withInput();
        }

        $credentialEmail = $loginEmail;

        if (!User::whereRaw('LOWER(email) = ?', [$credentialEmail])->exists()) {
            $student = Student::with('user')
                ->whereRaw('LOWER(email) = ?', [$loginEmail])
                ->whereNotNull('user_id')
                ->first();

            if ($student?->user) {
                $credentialEmail = strtolower($student->user->email);
            }
        }

        if (!Auth::attempt(['email' => $credentialEmail, 'password' => $request->password])) {
            return back()->withErrors([
                'email' => 'Invalid credentials.',
            ])->withInput();
        }

        $request->session()->regenerate();

        ActivityLog::record(
            Auth::id(),
            'auth',
            'login',
            'User',
            Auth::id(),
            'User logged in using ' . $loginEmail . '.',
            $request->ip()
        );

        return $this->redirectByRole(Auth::user()->role);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            ActivityLog::record(
                $user->id,
                'auth',
                'logout',
                'User',
                $user->id,
                'User logged out.',
                $request->ip()
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function createUserFromRegistration(Request $request, ?RoleReferenceCode $referenceCode): User
    {
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

        return $user;
    }

    private function normalizeInstitutionalEmail(?string $emailLocal): string
    {
        $emailLocal = trim(strtolower((string) $emailLocal));

        if ($emailLocal === '') {
            return '';
        }

        if (str_contains($emailLocal, '@')) {
            [$localPart] = explode('@', $emailLocal, 2);
            $emailLocal = $localPart;
        }

        return $emailLocal . '@agnusdei.local';
    }

    private function normalizeInstitutionalLogin(?string $value): string
    {
        $value = trim(strtolower((string) $value));

        if ($value === '') {
            return '';
        }

        if (str_contains($value, '@')) {
            return $value;
        }

        return $value . '@agnusdei.local';
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
