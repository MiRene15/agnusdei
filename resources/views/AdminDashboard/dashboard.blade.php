@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<style>
    .dashboard-bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 24px;
    }

    @media (max-width: 991px) {
        .dashboard-bottom-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="page-intro">
    <h4>Dashboard</h4>
    <p>Monitor users, admissions, collections, balances, and recent activity across the system.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-sub">Registered accounts in the system</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Students</div>
        <div class="stat-value">{{ $totalStudents }}</div>
        <div class="stat-sub">Currently recorded students</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teachers</div>
        <div class="stat-value">{{ $totalTeachers }}</div>
        <div class="stat-sub">Teaching staff accounts</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Classes</div>
        <div class="stat-value">{{ $totalClasses }}</div>
        <div class="stat-sub">Available class records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Admissions</div>
        <div class="stat-value">{{ $totalAdmissions }}</div>
        <div class="stat-sub">Submitted admission requests</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Collected Payments</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
        <div class="stat-sub">Total school collections</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Outstanding Balance</div>
        <div class="stat-value">₱{{ number_format($totalOutstanding, 2) }}</div>
        <div class="stat-sub">Remaining unpaid balances</div>
    </div>
</div>

<div class="dashboard-bottom-grid">
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
                        • {{ $announcement->posted_at->format('M d, Y h:i A') }}
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
                    <th>Assignment</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentCodes as $code)
                    <tr>
                        <td style="font-weight:700; color:#001e82;">{{ $code->code }}</td>
                        <td>{{ ucfirst($code->role) }}</td>
                        <td>
                            @if($code->role === 'teacher')
                                {{ $code->subject->subject_name ?? 'No subject' }}
                                <br>
                                <small style="color:#64748b;">
                                    {{ $code->grade_level ?? 'N/A' }} /
                                    {{ $code->section ?? 'N/A' }} /
                                    {{ $code->school_year ?? 'N/A' }}
                                </small>
                            @else
                                <span style="color:#64748b;">Not applicable</span>
                            @endif
                        </td>
                        <td>
                            @if($code->is_used)
                                <span style="display:inline-block; padding:6px 10px; background:#dcfce7; color:#166534; border-radius:999px; font-size:12px; font-weight:600;">
                                    Used
                                </span>
                            @elseif(!$code->is_active)
                                <span style="display:inline-block; padding:6px 10px; background:#fee2e2; color:#991b1b; border-radius:999px; font-size:12px; font-weight:600;">
                                    Inactive
                                </span>
                            @else
                                <span style="display:inline-block; padding:6px 10px; background:#dbeafe; color:#1d4ed8; border-radius:999px; font-size:12px; font-weight:600;">
                                    Active
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:#64748b;">No recent reference codes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top:16px;">
        <a href="{{ route('admin.reference-codes.index') }}" class="btn btn-primary">Manage Reference Codes</a>
    </div>
</div>

@endsection