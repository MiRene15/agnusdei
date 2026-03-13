@extends('layouts.student')

@section('title', 'Schedule')

@section('content')

<div class="page-intro">
    <h4>My Class Schedule</h4>
    <p>View your assigned weekly schedule based on your enrolled classes.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">LRN</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $student->lrn ?? 'N/A' }}
        </div>
        <div class="stat-sub">Learner Reference Number</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Grade Level</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $student->grade_level ?? 'N/A' }}
        </div>
        <div class="stat-sub">Current assigned grade</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Section</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $student->section ?? 'N/A' }}
        </div>
        <div class="stat-sub">Current assigned section</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">School Year</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $student->school_year ?? 'N/A' }}
        </div>
        <div class="stat-sub">Current academic year</div>
    </div>
</div>

<div class="card">
    <h4>Weekly Schedule</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Room</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedule as $item)
                    <tr>
                        <td>{{ $item['subject_name'] ?? '-' }}</td>
                        <td>{{ $item['day_of_week'] ?? '-' }}</td>
                        <td>{{ $item['start_time'] ?? '-' }}</td>
                        <td>{{ $item['end_time'] ?? '-' }}</td>
                        <td>{{ $item['room'] ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No schedule available yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h4>Schedule Notes</h4>
    <ul class="mini-list">
        <li>Please follow your assigned class times strictly.</li>
        <li>Contact the registrar if your section or class schedule is incorrect.</li>
        <li>Your schedule will automatically update once classes are assigned in the database.</li>
    </ul>
</div>

@endsection