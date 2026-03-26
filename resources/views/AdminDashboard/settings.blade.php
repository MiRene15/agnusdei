@extends('layouts.admin')

@section('title', 'Admin Settings')

@section('content')

<div class="page-intro">
    <h4>Settings</h4>
    <p>Update your account information, keep your admin profile secure, and control live academic events.</p>
</div>

<div class="card">
    <h4>Profile Settings</h4>

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                class="form-control"
                value="{{ old('name', $admin->name) }}"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input
                type="email"
                id="email"
                class="form-control"
                value="{{ $admin->email }}"
                readonly
            >
        </div>

        <div class="form-group">
            <label for="contact_number">Contact Number</label>
            <input
                type="text"
                id="contact_number"
                name="contact_number"
                class="form-control"
                value="{{ old('contact_number', $admin->contact_number) }}"
            >
        </div>

        <div class="grid-2">
            <div class="form-group">
                <label for="password">New Password</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Enter new password"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control"
                    placeholder="Confirm new password"
                >
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Changes</button>
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
