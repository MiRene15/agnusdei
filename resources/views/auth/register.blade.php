@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div style="max-width: 550px; margin: 80px auto; padding: 0 20px;">
    <div style="
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        padding: 40px 30px;
    ">
        <h2 style="
            text-align: center;
            color: #001e82;
            margin-bottom: 10px;
            font-size: 30px;
            font-weight: 600;
        ">
            Register
        </h2>

        <p style="
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
            font-size: 15px;
        ">
            Create your Agnus Dei School Systems account
        </p>

        @if ($errors->any())
            <div style="
                background: #fee2e2;
                color: #991b1b;
                padding: 12px 15px;
                border-radius: 10px;
                margin-bottom: 20px;
                font-size: 14px;
            ">
                <ul style="margin:0; padding-left:18px;">
                    @foreach ($errors->all() as $error)
                        <li style="margin-bottom:4px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
            @csrf

            <div style="margin-bottom: 18px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#334155;">
                    Full Name
                </label>
                <input 
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    required
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #cbd5e1;
                        border-radius:10px;
                        font-size:15px;
                        outline:none;
                    "
                >
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#334155;">
                    Email
                </label>
                <input 
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #cbd5e1;
                        border-radius:10px;
                        font-size:15px;
                        outline:none;
                    "
                >
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#334155;">
                    Contact Number
                </label>
                <input 
                    type="text"
                    name="contact_number"
                    value="{{ old('contact_number') }}"
                    placeholder="Enter your contact number"
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #cbd5e1;
                        border-radius:10px;
                        font-size:15px;
                        outline:none;
                    "
                >
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#334155;">
                    Role
                </label>
                <select 
                    name="role"
                    required
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #cbd5e1;
                        border-radius:10px;
                        font-size:15px;
                        outline:none;
                        background:#fff;
                    "
                >
                    <option value="">Select Role</option>
                    <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                    <option value="parent" {{ old('role') == 'parent' ? 'selected' : '' }}>Parent</option>
                    <option value="registrar" {{ old('role') == 'registrar' ? 'selected' : '' }}>Registrar</option>
                    <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#334155;">
                    Password
                </label>
                <input 
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #cbd5e1;
                        border-radius:10px;
                        font-size:15px;
                        outline:none;
                    "
                >
            </div>

            <div style="margin-bottom: 22px;">
                <label style="display:block; margin-bottom:8px; font-weight:500; color:#334155;">
                    Confirm Password
                </label>
                <input 
                    type="password"
                    name="password_confirmation"
                    placeholder="Confirm your password"
                    required
                    style="
                        width:100%;
                        padding:12px 14px;
                        border:1px solid #cbd5e1;
                        border-radius:10px;
                        font-size:15px;
                        outline:none;
                    "
                >
            </div>

            <button type="submit" style="
                width:100%;
                background:#001e82;
                color:#fff;
                border:none;
                padding:13px;
                border-radius:10px;
                font-size:16px;
                font-weight:600;
                cursor:pointer;
                transition:0.3s ease;
            ">
                Register
            </button>
        </form>

        <p style="
            margin-top: 22px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        ">
            Already have an account?
            <a href="{{ route('login') }}" style="color:#001e82; font-weight:600; text-decoration:none;">
                Login here
            </a>
        </p>
    </div>
</div>

@endsection