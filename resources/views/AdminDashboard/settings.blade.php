@extends('layouts.admin')

@section('title', 'Admin Settings')

@section('content')
<div class="page-intro">
    <h4>Settings</h4>
    <p>Admin settings only keep password security and academic event controls in one place.</p>
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

<div class="card">
    <h4>Admin Password</h4>
    <p style="color:#64748b; margin-bottom:18px;">Your email stays fixed. Update only the admin password here.</p>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; margin-bottom:18px;">
        <div style="padding:16px; border:1px solid #dbeafe; border-radius:14px; background:#eff6ff;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#1d4ed8;">Admin Account</div>
            <div style="font-weight:700; color:#1e3a8a; margin-top:6px;">{{ $admin->name }}</div>
            <div style="color:#475569; margin-top:6px;">{{ $admin->email }}</div>
        </div>
        <div style="padding:16px; border:1px solid #dcfce7; border-radius:14px; background:#f0fdf4;">
            <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#166534;">Password Rules</div>
            <div style="color:#166534; margin-top:6px; line-height:1.7; font-size:14px;">Minimum 8 characters, 1 uppercase, 1 lowercase, 2 numbers, and 1 special character.</div>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" style="display:grid; gap:16px; max-width:760px;">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="password">New Password</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Create a stronger admin password">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Confirm new admin password">
        </div>
        <div>
            <button type="submit" class="btn btn-primary">Update Password</button>
        </div>
    </form>
</div>

<div class="card">
    <h4>Academic Event Controls</h4>
    <p style="color:#64748b; margin-bottom:16px;">Enable or disable live rules that affect enrollment and grade workflows.</p>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event->event_name }}</td>
                        <td>{{ $event->description ?? '-' }}</td>
                        <td>{{ $event->is_enabled ? 'Enabled' : 'Disabled' }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.events.toggle', $event->id) }}">
                                @csrf
                                <button type="submit" class="btn {{ $event->is_enabled ? 'btn-danger' : 'btn-primary' }}">
                                    {{ $event->is_enabled ? 'Disable' : 'Enable' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#64748b;">No academic events configured.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
