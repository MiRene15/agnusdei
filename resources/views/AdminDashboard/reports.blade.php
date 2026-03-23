@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

<div class="page-intro">
    <h4>Reports</h4>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Students</div>
        <div class="stat-value">{{ $studentCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teachers</div>
        <div class="stat-value">{{ $teacherCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Classes</div>
        <div class="stat-value">{{ $classCount }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Approved</div>
        <div class="stat-value">{{ $approvedAdmissions }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending</div>
        <div class="stat-value">{{ $pendingAdmissions }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Collected</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
    </div>
</div>

<div class="card">
    <h4>Users by Role</h4>
    <ul class="mini-list">
        @foreach($usersByRole as $role => $count)
            <li>{{ ucfirst($role) }} — {{ $count }}</li>
        @endforeach
    </ul>
</div>

@endsection