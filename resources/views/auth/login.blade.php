@extends('layouts.app')

@section('title', 'Login')

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
            Login
        </h2>

        <p style="
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
            font-size: 15px;
        ">
            Sign in to your Agnus Dei School Systems account
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
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

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

            <div style="margin-bottom: 22px;">
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

            <div style="margin-bottom: 22px; display:flex; align-items:center; gap:8px;">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="font-size:14px; color:#475569;">Remember me</label>
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
                Login
            </button>
        </form>

        <p style="
            margin-top: 22px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
        ">
            Don't have an account?
            <a href="{{ route('register') }}" style="color:#001e82; font-weight:600; text-decoration:none;">
                Register here
            </a>
        </p>
    </div>
</div>

@endsection