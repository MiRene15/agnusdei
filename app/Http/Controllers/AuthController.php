<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'nullable|string|max:20',
            'role' => 'required|in:student,parent,registrar,teacher,admin,cashier',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'role' => strtolower($request->role),
            'password' => $request->password,
        ]);

        if ($user->role === 'student') {
            $nameParts = explode(' ', trim($request->name), 2);

            Student::create([
                'user_id' => $user->id,
                'parent_id' => null,
                'admission_id' => null,
                'student_number' => 'STU-' . strtoupper(Str::random(8)),
                'first_name' => $nameParts[0] ?? $request->name,
                'last_name' => $nameParts[1] ?? '-',
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

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    public function loginUser(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            $detectedRole = $this->detectRoleFromEmail($user->email);

            if ($detectedRole && $detectedRole !== $user->role) {
                Auth::logout();

                return back()->withErrors([
                    'email' => 'Email role does not match account role.',
                ])->onlyInput('email');
            }

            return $this->redirectByRole($user->role);
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function detectRoleFromEmail($email)
    {
        $email = strtolower($email);

        return match (true) {
            str_contains($email, 'admin') => 'admin',
            str_contains($email, 'registrar') => 'registrar',
            str_contains($email, 'teacher') => 'teacher',
            str_contains($email, 'parent') => 'parent',
            str_contains($email, 'cashier') => 'cashier',
            str_contains($email, 'student') => 'student',
            default => null,
        };
    }

    private function redirectByRole($role)
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
