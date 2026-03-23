@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')

<div class="page-intro">
    <h4>Dashboard</h4>
    <p>Monitor users, admissions, finances, and system activity.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-sub">All system users</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Students</div>
        <div class="stat-value">{{ $totalStudents }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teachers</div>
        <div class="stat-value">{{ $totalTeachers }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Classes</div>
        <div class="stat-value">{{ $totalClasses }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Admissions</div>
        <div class="stat-value">{{ $totalAdmissions }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Collected</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Outstanding</div>
        <div class="stat-value">₱{{ number_format($totalOutstanding, 2) }}</div>
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
                        <tr><td colspan="3">No users found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <h4>Announcements</h4>
        <ul class="mini-list">
            @forelse($recentAnnouncements as $a)
                <li>
                    <strong>{{ $a->title }}</strong><br>
                    {{ ucfirst($a->audience) }}
                </li>
            @empty
                <li>No announcements yet.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection