@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-hero auth-hero-student">
            <div>
                <div class="auth-kicker">Agnus Dei Portal</div>
                <h2>Student Registration</h2>
                <p>All portal accounts use the institutional school email format. Students still require an active student reference code.</p>
            </div>
        </div>

        <div class="auth-body">
            @if ($errors->any())
                <div class="auth-alert auth-alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="auth-tip-grid">
                <div class="auth-tip auth-tip-blue">
                    <div class="auth-tip-title">Institutional Email Only</div>
                    <p>Every account uses the fixed <strong>@agnusdei.local</strong> domain. Users only complete the username part.</p>
                </div>
                <div class="auth-tip auth-tip-green">
                    <div class="auth-tip-title">Password Rule</div>
                    <p>Use 8 or more characters with uppercase, lowercase, 2 numbers, and 1 special character.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('register.post') }}" class="auth-form">
                @csrf
                <input type="hidden" name="role" value="student">

                <div class="auth-field">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                </div>

                <div class="auth-field">
                    <label>Registration Type</label>
                    <input type="text" value="Student Account" readonly>
                    {{-- Parent public registration intentionally kept hidden in code. --}}
                </div>

                <div class="auth-field">
                    <label>Institutional Email</label>
                    <div class="email-fixed-wrap">
                        <input type="text" name="email_local" id="email_local" value="{{ old('email_local', str_replace('@agnusdei.local', '', (string) old('email'))) }}" placeholder="portal.username" autocomplete="off" required>
                        <span>@agnusdei.local</span>
                    </div>
                    <small>Only the username part is needed. The school domain is fixed automatically.</small>
                </div>

                <div class="auth-field" id="reference_code_wrap">
                    <label>Student Reference Code</label>
                    <input type="text" name="reference_code" id="reference_code" value="{{ old('reference_code') }}" placeholder="Enter your student verification code" style="text-transform:uppercase;">
                    <small>This verifies that the student is an upcoming Agnus Dei learner.</small>
                </div>

                <div class="auth-field">
                    <label>Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" placeholder="09XXXXXXXXX or +639XXXXXXXXX" pattern="^(09\d{9}|\+639\d{9})$">
                </div>

                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create a strong password" required>
                </div>

                <div class="auth-field">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" placeholder="Confirm your password" required>
                </div>

                <button type="submit" class="auth-submit">Create Account</button>
            </form>

            <div class="auth-links">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                <p>School staff? <a href="{{ route('staff.register') }}">Use staff portal</a></p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-shell { max-width: 700px; margin: 70px auto; padding: 0 20px; }
.auth-card { background:#fff; border-radius:24px; overflow:hidden; border:1px solid #dbe2ea; box-shadow:0 20px 44px rgba(15,23,42,0.10); }
.auth-hero { padding:30px 32px; color:#fff; }
.auth-hero-student { background:linear-gradient(135deg, #0f172a, #1d4ed8); }
.auth-kicker { font-size:12px; text-transform:uppercase; letter-spacing:.14em; opacity:.8; margin-bottom:10px; }
.auth-hero h2 { margin:0; font-size:31px; line-height:1.15; }
.auth-hero p { margin:10px 0 0; color:rgba(255,255,255,.88); line-height:1.7; }
.auth-body { padding:28px 32px 32px; }
.auth-alert { border-radius:14px; padding:14px 16px; margin-bottom:18px; }
.auth-alert ul { margin:0; padding-left:18px; }
.auth-alert-error { background:#fee2e2; color:#991b1b; border:1px solid #fecaca; }
.auth-tip-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:18px; }
.auth-tip { padding:16px; border-radius:16px; border:1px solid; }
.auth-tip-title { font-size:12px; text-transform:uppercase; letter-spacing:.08em; font-weight:700; margin-bottom:8px; }
.auth-tip p { margin:0; line-height:1.6; font-size:14px; }
.auth-tip-blue { background:#eff6ff; border-color:#bfdbfe; color:#1e3a8a; }
.auth-tip-green { background:#f0fdf4; border-color:#bbf7d0; color:#166534; }
.auth-form { display:grid; gap:18px; }
.auth-field label { display:block; margin-bottom:8px; color:#334155; font-weight:600; }
.auth-field small { display:block; margin-top:6px; color:#64748b; }
.auth-field input, .auth-field select { width:100%; padding:13px 14px; border:1px solid #cbd5e1; border-radius:12px; font-size:15px; outline:none; background:#fff; }
.auth-field input:focus, .auth-field select:focus { border-color:#1d4ed8; box-shadow:0 0 0 3px rgba(29,78,216,.12); }
.email-fixed-wrap { display:grid; grid-template-columns:1fr auto; align-items:center; border:1px solid #cbd5e1; border-radius:12px; overflow:hidden; background:#fff; }
.email-fixed-wrap input { border:none; border-radius:0; }
.email-fixed-wrap span { padding:0 14px; height:100%; display:flex; align-items:center; background:#eff6ff; color:#1e3a8a; font-weight:700; border-left:1px solid #cbd5e1; }
.auth-submit { width:100%; border:none; border-radius:12px; padding:14px; background:#0f172a; color:#fff; font-size:16px; font-weight:700; cursor:pointer; }
.auth-links { margin-top:22px; display:grid; gap:8px; text-align:center; color:#64748b; }
.auth-links a { color:#1d4ed8; font-weight:700; text-decoration:none; }
@media (max-width: 640px) { .auth-shell { margin:40px auto; } .auth-body, .auth-hero { padding:24px 20px; } .email-fixed-wrap { grid-template-columns:1fr; } .email-fixed-wrap span { border-left:none; border-top:1px solid #cbd5e1; justify-content:flex-start; padding:10px 14px; } }
</style>

@endsection
