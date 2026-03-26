@extends('layouts.' . $role)

@section('title', 'Account Settings')

@section('content')
<div class="page-intro">
    <h4>Account Settings</h4>
    <p>Keep your {{ ucfirst($role) }} account secure by updating your password here.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">
        {{ session('success') }}
    </div>
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

<div class="card" style="max-width:780px;">
    <h4>Password Security</h4>
    <p style="color:#64748b; margin-bottom:18px;">Your sign-in email is fixed. Only your password can be changed here.</p>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:18px;">
        <div style="padding:16px; border:1px solid #dbeafe; border-radius:14px; background:#eff6ff;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#1d4ed8;">Account</div>
            <div style="font-weight:700; color:#1e3a8a; margin-top:6px;">{{ $user->name }}</div>
            <div style="color:#475569; margin-top:6px;">{{ $user->email }}</div>
        </div>
        <div style="padding:16px; border:1px solid #dcfce7; border-radius:14px; background:#f0fdf4;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#166534;">Password Rules</div>
            <div style="color:#166534; margin-top:6px; line-height:1.7; font-size:14px;">Minimum 8 characters, at least 1 uppercase letter, 1 lowercase letter, 2 numbers, and 1 special character.</div>
        </div>
    </div>

    <form method="POST" action="{{ route($routeName) }}" style="display:grid; gap:16px;">
        @csrf
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" class="form-control" placeholder="Enter your current password" required>
        </div>
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Create a stronger password" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm New Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm your new password" required>
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
    </form>
</div>
@endsection
