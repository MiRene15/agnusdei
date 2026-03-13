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
    /*
    |--------------------------------------------------------------------------
    | Show Login Page
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        return view('auth.login');
    }

    /*
    |--------------------------------------------------------------------------
    | Process Login
    |--------------------------------------------------------------------------
    */

    public function loginPost(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($request->only('email','password'), $request->remember)) {

            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials.'
        ])->withInput();
    }

    /*
    |--------------------------------------------------------------------------
    | Show Register Page
    |--------------------------------------------------------------------------
    */

    public function register()
    {
        return view('auth.register');
    }

    /*
    |--------------------------------------------------------------------------
    | Process Registration
    |--------------------------------------------------------------------------
    */

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'contact_number' => 'nullable|string|max:20',
            'role' => 'required|in:student,parent,teacher,registrar,admin,cashier',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->contact_number,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Auto-create Student Profile
        |--------------------------------------------------------------------------
        */

        if ($request->role === 'student') {

            Student::create([
                'user_id' => $user->id,
                'student_number' => 'STU-' . strtoupper(Str::random(6)),
                'first_name' => $request->name,
                'last_name' => '',
                'grade_level' => 'Pending',
                'status' => 'pending'
            ]);
        }

        Auth::login($user);

        return $this->redirectByRole($user->role);
    }

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Redirect
    |--------------------------------------------------------------------------
    */

    private function redirectByRole($role)
    {
        switch ($role) {

            case 'admin':
                return redirect()->route('admin.dashboard');

            case 'registrar':
                return redirect()->route('registrar.dashboard');

            case 'teacher':
                return redirect()->route('teacher.dashboard');

            case 'parent':
                return redirect()->route('parent.dashboard');

            case 'cashier':
                return redirect()->route('cashier.dashboard');

            case 'student':
                return redirect()->route('student.portal.check');

            default:
                return redirect('/');
        }
    }
}