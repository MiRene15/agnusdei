@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-shell">
    <div class="auth-card">
        <div class="auth-hero auth-hero-login">
            <div>
                <div class="auth-kicker">Agnus Dei Portal</div>
                <h2>Account Login</h2>
                <p>All accounts sign in using the institutional school email format. Only the username part is needed below.</p>
            </div>
        </div>

        <div class="auth-body">
            @if ($errors->any())
                <div class="auth-alert auth-alert-error">{{ $errors->first() }}</div>
            @endif

            <div class="auth-tip-grid">
                <div class="auth-tip auth-tip-blue">
                    <div class="auth-tip-title">Institutional Login Only</div>
                    <p>Enter your account username and the system completes the fixed <strong>@agnusdei.local</strong> domain automatically.</p>
                </div>
                <div class="auth-tip auth-tip-green">
                    <div class="auth-tip-title">One Sign-In Format</div>
                    <p>Student, parent, teacher, cashier, and registrar accounts now all follow the same school email standard.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('login.post') }}" class="auth-form" id="login-form">
                @csrf

                <div class="auth-field">
                    <label>Institutional Email</label>
                    <div class="email-fixed-wrap">
                        <input type="text" name="email_local" id="email_local" value="{{ old('email_local', str_replace('@agnusdei.local', '', (string) old('email'))) }}" placeholder="account.username" autocomplete="username" required>
                        <span>@agnusdei.local</span>
                    </div>
                    <small>Paste the full institutional email if you prefer. The school domain is the only accepted format.</small>
                </div>

                <div class="auth-field">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                </div>

                <button type="submit" class="auth-submit auth-submit-blue">Login</button>
            </form>

            <div class="auth-links">
                <p>Need an account? <a href="{{ route('register') }}">Register here</a></p>
                <p>Staff member? <a href="{{ route('staff.register') }}">Use staff registration</a></p>
            </div>
        </div>
    </div>
</div>

<style>
.auth-shell { max-width: 700px; margin: 70px auto; padding: 0 20px; }
.auth-card { background:#fff; border-radius:24px; overflow:hidden; border:1px solid #dbe2ea; box-shadow:0 20px 44px rgba(15,23,42,0.10); }
.auth-hero { padding:30px 32px; color:#fff; }
.auth-hero-login { background:linear-gradient(135deg, #0f172a, #334155); }
.auth-kicker { font-size:12px; text-transform:uppercase; letter-spacing:.14em; opacity:.8; margin-bottom:10px; }
.auth-hero h2 { margin:0; font-size:31px; line-height:1.15; }
.auth-hero p { margin:10px 0 0; color:rgba(255,255,255,.88); line-height:1.7; }
.auth-body { padding:28px 32px 32px; }
.auth-alert { border-radius:14px; padding:14px 16px; margin-bottom:18px; }
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
.auth-field input { width:100%; padding:13px 14px; border:1px solid #cbd5e1; border-radius:12px; font-size:15px; outline:none; background:#fff; }
.auth-field input:focus { border-color:#1d4ed8; box-shadow:0 0 0 3px rgba(29,78,216,.12); }
.email-fixed-wrap { display:grid; grid-template-columns:1fr auto; align-items:center; border:1px solid #cbd5e1; border-radius:12px; overflow:hidden; background:#fff; }
.email-fixed-wrap input { border:none; border-radius:0; }
.email-fixed-wrap span { padding:0 14px; height:100%; display:flex; align-items:center; background:#eff6ff; color:#1e3a8a; font-weight:700; border-left:1px solid #cbd5e1; }
.auth-submit { width:100%; border:none; border-radius:12px; padding:14px; color:#fff; font-size:16px; font-weight:700; cursor:pointer; }
.auth-submit-blue { background:#001e82; }
.auth-links { margin-top:22px; display:grid; gap:8px; text-align:center; color:#64748b; }
.auth-links a { color:#1d4ed8; font-weight:700; text-decoration:none; }
@media (max-width: 640px) { .auth-shell { margin:40px auto; } .auth-body, .auth-hero { padding:24px 20px; } .email-fixed-wrap { grid-template-columns:1fr; } .email-fixed-wrap span { border-left:none; border-top:1px solid #cbd5e1; justify-content:flex-start; padding:10px 14px; } }
</style>
@endsection
