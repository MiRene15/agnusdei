@extends('layouts.admin')

@section('title', 'Reports')

@section('content')

<div class="page-intro">
    <h4>Reports</h4>
    <p>Review overall academic, admission, financial, and user summaries.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Students</div>
        <div class="stat-value">{{ $studentCount }}</div>
        <div class="stat-sub">Total student records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Teachers</div>
        <div class="stat-value">{{ $teacherCount }}</div>
        <div class="stat-sub">Active teaching staff</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Classes</div>
        <div class="stat-value">{{ $classCount }}</div>
        <div class="stat-sub">Academic class entries</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Admissions</div>
        <div class="stat-value">{{ $admissionCount }}</div>
        <div class="stat-sub">Total admission applications</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Approved Admissions</div>
        <div class="stat-value">{{ $approvedAdmissions }}</div>
        <div class="stat-sub">Successfully processed requests</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Pending Admissions</div>
        <div class="stat-value">{{ $pendingAdmissions }}</div>
        <div class="stat-sub">Awaiting review</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Payments Collected</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
        <div class="stat-sub">{{ $paymentCount }} payment records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Outstanding Balance</div>
        <div class="stat-value">₱{{ number_format($totalOutstanding, 2) }}</div>
        <div class="stat-sub">{{ $billingCount }} billing records</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Users by Role</h4>

        <ul class="mini-list">
            @foreach($usersByRole as $role => $count)
                <li>{{ ucfirst($role) }} — <strong>{{ $count }}</strong></li>
            @endforeach
        </ul>
    </div>

    <div class="card">
        <h4>Quick Summary</h4>

        <ul class="mini-list">
            <li>Total Students — <strong>{{ $studentCount }}</strong></li>
            <li>Total Teachers — <strong>{{ $teacherCount }}</strong></li>
            <li>Total Classes — <strong>{{ $classCount }}</strong></li>
            <li>Approved Admissions — <strong>{{ $approvedAdmissions }}</strong></li>
            <li>Pending Admissions — <strong>{{ $pendingAdmissions }}</strong></li>
            <li>Total Collected — <strong>₱{{ number_format($totalCollected, 2) }}</strong></li>
        </ul>
    </div>
</div>

@endsection