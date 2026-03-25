<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\RoleReferenceCode;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function registerUser(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'nullable|string|max:20',
            'role' => 'required|in:student,parent,registrar,teacher,admin,cashier',
            'password' => 'required|string|min:8|confirmed',
            'reference_code' => 'nullable|string|max:100',
        ]);

        $role = strtolower((string) $request->role);
        $staffRoles = ['teacher', 'registrar', 'cashier', 'admin'];
        $referenceCode = null;

        if (in_array($role, $staffRoles, true)) {
            $request->validate([
                'reference_code' => 'required|string|max:100',
            ]);

            $referenceCode = RoleReferenceCode::where('code', strtoupper(trim((string) $request->reference_code)))
                ->where('role', $role)
                ->where('is_active', true)
                ->where('is_used', false)
                ->first();

            if (!$referenceCode) {
                return back()->withErrors([
                    'reference_code' => 'Invalid, inactive, or already used reference code.',
                ])->withInput();
            }

            if ($referenceCode->expires_at && now()->greaterThan($referenceCode->expires_at)) {
                return back()->withErrors([
                    'reference_code' => 'This reference code has already expired.',
                ])->withInput();
            }
        }

        $user = DB::transaction(function () use ($request, $role, $referenceCode) {
            $createdUser = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'role' => $role,
                'reference_code_id' => $referenceCode?->id,
                'password' => $request->password,
            ]);

            $nameParts = preg_split('/\s+/', trim((string) $request->name), 2);
            $firstName = $nameParts[0] ?? (string) $request->name;
            $lastName = $nameParts[1] ?? '-';

            if ($createdUser->role === 'student') {
                Student::create([
                    'user_id' => $createdUser->id,
                    'parent_id' => null,
                    'admission_id' => null,
                    'student_number' => 'STU-' . strtoupper(Str::random(8)),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'birth_date' => null,
                    'gender' => null,
                    'email' => $request->email,
                    'phone' => $request->contact_number,
                    'address' => null,
                    'grade_level' => 'Not Yet Assigned',
                    'section' => null,
                    'school_year' => null,
                    'status' => 'pending',
                ]);
            }

            if ($createdUser->role === 'teacher') {
                $teacher = Teacher::create([
                    'user_id' => $createdUser->id,
                    'teacher_number' => 'TCH-' . strtoupper(Str::random(8)),
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request->email,
                    'phone' => $request->contact_number,
                    'department' => $referenceCode?->grade_level,
                    'status' => 'active',
                ]);

                if ($referenceCode) {
                    Classes::query()
                        ->when($referenceCode->subject_id, fn ($q) => $q->where('subject_id', $referenceCode->subject_id))
                        ->when($referenceCode->section, fn ($q) => $q->where('section', $referenceCode->section))
                        ->when($referenceCode->grade_level, fn ($q) => $q->where('grade_level', $referenceCode->grade_level))
                        ->when($referenceCode->school_year, fn ($q) => $q->where('school_year', $referenceCode->school_year))
                        ->when($referenceCode->semester, fn ($q) => $q->where('semester', $referenceCode->semester))
                        ->update([
                            'teacher_id' => $teacher->id,
                        ]);
                }
            }

            if ($referenceCode) {
                $referenceCode->update([
                    'used_by' => $createdUser->id,
                    'is_used' => true,
                    'used_at' => now(),
                ]);
            }

            return $createdUser;
        });

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    public function loginUser(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            /** @var \App\Models\User|null $user */
            $user = Auth::user();

            if (!$user) {
                return redirect()->route('login')->withErrors([
                    'email' => 'Unable to log in right now. Please try again.',
                ]);
            }

            return $this->redirectByRole($user->role);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'registrar' => redirect()->route('registrar.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'parent' => redirect()->route('parent.dashboard'),
            'cashier' => redirect()->route('cashier.dashboard'),
            'student' => redirect()->route('student.portal.check'),
            default => redirect()->route('login')->withErrors([
                'email' => 'Role not recognized.',
            ]),
        };
    }
}