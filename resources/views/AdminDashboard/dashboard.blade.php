@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="page-intro">
    <h4>Dashboard</h4>
    <p>Monitor users, admissions, collections, balances, academic controls, and reference activity across the system.</p>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Total Users</div><div class="stat-value">{{ $totalUsers }}</div><div class="stat-sub">Registered accounts in the system</div></div>
    <div class="stat-card"><div class="stat-label">Students</div><div class="stat-value">{{ $totalStudents }}</div><div class="stat-sub">Currently recorded students</div></div>
    <div class="stat-card"><div class="stat-label">Teachers</div><div class="stat-value">{{ $totalTeachers }}</div><div class="stat-sub">Teaching staff accounts</div></div>
    <div class="stat-card"><div class="stat-label">Classes</div><div class="stat-value">{{ $totalClasses }}</div><div class="stat-sub">Available class records</div></div>
    <div class="stat-card"><div class="stat-label">Admissions</div><div class="stat-value">{{ $totalAdmissions }}</div><div class="stat-sub">Submitted admission requests</div></div>
    <div class="stat-card"><div class="stat-label">Collected Payments</div><div class="stat-value">PHP {{ number_format($totalCollected, 2) }}</div><div class="stat-sub">Total school collections</div></div>
    <div class="stat-card"><div class="stat-label">Outstanding Balance</div><div class="stat-value">PHP {{ number_format($totalOutstanding, 2) }}</div><div class="stat-sub">Remaining unpaid balances</div></div>
</div>

<div class="quick-actions" style="margin-bottom:24px;">
    <a href="{{ route('admin.users') }}" class="action-box">
        <h5>Manage Users</h5>
        <p>Open the user directory to review roles, contacts, and account access.</p>
    </a>
    <a href="{{ route('admin.announcements') }}" class="action-box">
        <h5>Post Announcements</h5>
        <p>Publish updates that appear on the home page and internal portals.</p>
    </a>
    <a href="{{ route('admin.reference-codes') }}" class="action-box">
        <h5>Reference Codes</h5>
        <p>Create and manage student and staff onboarding verification codes.</p>
    </a>
    <a href="{{ route('admin.settings') }}" class="action-box">
        <h5>Academic Controls</h5>
        <p>Turn enrollment and grade-related system events on or off from one place.</p>
    </a>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Recent Users</h4>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ ucfirst($user->role) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; color:#64748b;">No recent users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h4>Recent Announcements</h4>
        <ul class="mini-list">
            @forelse($recentAnnouncements as $announcement)
                <li>
                    <strong>{{ $announcement->title }}</strong><br>
                    {{ ucfirst($announcement->audience) }}
                    @if($announcement->posted_at)
                        | {{ $announcement->posted_at->format('M d, Y h:i A') }}
                    @endif
                </li>
            @empty
                <li>No announcements available.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="card">
    <h4>Recent Reference Codes</h4>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Role</th>
                    <th>Description</th>
                    <th>Usage</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentCodes as $code)
                    <tr>
                        <td style="font-weight:700; color:#001e82;">{{ $code->code }}</td>
                        <td>{{ ucfirst($code->role) }}</td>
                        <td>{{ $code->description ?? 'No description' }}</td>
                        <td>{{ $code->used_count }} @if($code->max_uses)/ {{ $code->max_uses }} @else / Unlimited @endif</td>
                        <td>{{ $code->is_active ? 'Active' : 'Inactive' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#64748b;">No recent reference codes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        <a href="{{ route('admin.reference-codes') }}" class="btn btn-primary">Manage Reference Codes</a>
    </div>
</div>
@endsection
