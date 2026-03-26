@extends('layouts.teacher')

@section('title', 'Teacher Dashboard')

@section('content')

<div class="page-intro">
    <h4>Teacher Dashboard</h4>
    <p>View your teaching load, students, and weekly schedule overview.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">
        {{ session('error') }}
    </div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Teacher</div>
        <div class="stat-value" style="font-size:20px; line-height:1.3;">
            {{ $teacher ? $teacher->first_name . ' ' . $teacher->last_name : 'Not Linked' }}
        </div>
        <div class="stat-sub">
            {{ $teacher->department ?? 'No department assigned' }}
        </div>
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
        <h4 style="margin-bottom:14px;">Assigned Classes</h4>

        @forelse($classes as $class)
            <div style="padding:12px 0; border-bottom:1px solid #e2e8f0;">
                <div style="font-weight:600; color:#0f172a;">
                    {{ $class->subject->subject_name ?? '-' }}
                    ({{ $class->subject->subject_code ?? '-' }})
                </div>
                <div style="font-size:14px; color:#64748b; margin-top:4px;">
                    {{ $class->grade_level }} • {{ $class->section }} • {{ $class->school_year }}
                </div>
            </div>
        @empty
            <p style="color:#64748b;">No assigned classes yet.</p>
        @endforelse
    </div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Schedule Preview</h4>

        @forelse($upcomingSchedules as $schedule)
            <div style="padding:12px 0; border-bottom:1px solid #e2e8f0;">
                <div style="font-weight:600; color:#0f172a;">
                    {{ $schedule['subject_name'] }}
                </div>
                <div style="font-size:14px; color:#64748b; margin-top:4px;">
                    {{ $schedule['grade_level'] }} • {{ $schedule['section'] }}
                </div>
                <div style="font-size:14px; color:#334155; margin-top:4px;">
                    {{ $schedule['day_of_week'] }} |
                    {{ \Carbon\Carbon::parse($schedule['start_time'])->format('h:i A') }}
                    -
                    {{ \Carbon\Carbon::parse($schedule['end_time'])->format('h:i A') }}
                    | Room: {{ $schedule['room'] ?? '-' }}
                </div>
            </div>
        @empty
            <p style="color:#64748b;">No schedule available yet.</p>
        @endforelse
    </div>
</div>

@endsection