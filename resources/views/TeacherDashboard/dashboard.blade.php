@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')

<div class="page-intro">
    <h4>Teacher Dashboard</h4>
    <p>View teaching load, total students, and class schedules.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Teacher</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'Not Linked' }}
        </div>
        <div class="stat-sub">Assigned teacher profile</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Classes</div>
        <div class="stat-value">{{ $totalClasses }}</div>
        <div class="stat-sub">Assigned teaching classes</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Students</div>
        <div class="stat-value">{{ $totalStudents }}</div>
        <div class="stat-sub">Students under your classes</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Assigned Classes</h4>
        <ul class="mini-list">
            @forelse($classes as $class)
                <li>
                    {{ $class->subject->subject_name ?? '-' }}
                    — {{ $class->grade_level }} / {{ $class->section }}
                </li>
            @empty
                <li>No assigned classes yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <h4>Schedule Preview</h4>
        <ul class="mini-list">
            @forelse($upcomingSchedules as $schedule)
                <li>
                    {{ $schedule['subject_name'] }} - {{ $schedule['day_of_week'] }}
                    ({{ $schedule['start_time'] }} - {{ $schedule['end_time'] }})
                </li>
            @empty
                <li>No schedule available yet.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection