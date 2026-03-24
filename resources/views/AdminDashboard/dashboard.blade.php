@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

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
                            <td colspan="3">No recent users found.</td>
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

@endsection