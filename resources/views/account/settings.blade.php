@extends('layouts.' . $role)

@section('title', 'Account Settings')

@section('content')
<div class="page-intro">
    <h4>Account Settings</h4>
    <p>Keep your {{ ucfirst($role) }} account secure by updating your password here.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">
        <strong>Please fix the following:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card settings-card">
    <h4>Password Security</h4>
    <p class="settings-copy">Your sign-in email is fixed. Only your password can be changed here.</p>

    <div class="settings-summary-grid">
        <div class="settings-summary settings-summary-blue">
            <div class="summary-label">Account</div>
            <div class="summary-value">{{ $user->name }}</div>
            <div class="summary-copy">{{ $user->email }}</div>
        </div>
        <div class="settings-summary settings-summary-green">
            <div class="summary-label">Password Rules</div>
            <div class="summary-copy">Minimum 8 characters, at least 1 uppercase letter, 1 lowercase letter, 2 numbers, and 1 special character.</div>
        </div>
    </div>

    <form method="POST" action="{{ route($routeName) }}" class="settings-form">
        @csrf
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="settings-input" placeholder="Enter your current password" required>
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="settings-input" placeholder="Create a stronger password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="settings-input" placeholder="Confirm your new password" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
    </form>
</div>

<style>
.settings-card { max-width:780px; }
.settings-copy { color:#64748b; margin-bottom:18px; }
.settings-summary-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:18px; }
.settings-summary { padding:16px; border-radius:16px; border:1px solid; }
.settings-summary-blue { border-color:#dbeafe; background:#eff6ff; }
.settings-summary-green { border-color:#dcfce7; background:#f0fdf4; }
.summary-label { font-size:12px; text-transform:uppercase; letter-spacing:.08em; font-weight:700; color:#475569; }
.summary-value { font-weight:700; color:#1e3a8a; margin-top:6px; }
.summary-copy { color:#475569; margin-top:6px; line-height:1.7; font-size:14px; }
.settings-form { display:grid; gap:16px; }
.settings-input {
    width:100%; padding:14px 16px; border:1px solid #d7e2f0; border-radius:16px; font-size:14px; background:linear-gradient(180deg, #ffffff, #f8fbff); outline:none; color:#0f172a; box-shadow:inset 0 1px 2px rgba(15, 23, 42, 0.04);
}
.settings-input:focus { border-color:#2563eb; box-shadow:0 0 0 4px rgba(37,99,235,0.12); }
</style>
@endsection
