@extends('layouts.student')

@section('title', 'Schedule')

@section('content')
@php
    $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $grouped = collect($schedule)->sortBy(fn ($item) => array_search($item['day_of_week'], $dayOrder, true))->groupBy('day_of_week');
@endphp

<div class="page-intro">
    <h4>My Class Schedule</h4>
    <p>Your weekly classes are grouped by day for a cleaner and easier-to-follow view.</p>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">LRN</div><div class="stat-value" style="font-size:20px;">{{ $student->lrn ?? 'N/A' }}</div><div class="stat-sub">Learner reference number</div></div>
    <div class="stat-card"><div class="stat-label">Grade Level</div><div class="stat-value" style="font-size:20px;">{{ $student->grade_level ?? 'N/A' }}</div><div class="stat-sub">Current assigned grade</div></div>
    <div class="stat-card"><div class="stat-label">Section</div><div class="stat-value" style="font-size:20px;">{{ $student->section ?? 'N/A' }}</div><div class="stat-sub">Current assigned section</div></div>
    <div class="stat-card"><div class="stat-label">School Year</div><div class="stat-value" style="font-size:20px;">{{ $student->school_year ?? 'N/A' }}</div><div class="stat-sub">Current academic year</div></div>
</div>

<div class="quick-actions" style="margin-bottom:24px;">
    <div class="action-box" style="pointer-events:none;">
        <h5>Read By Day</h5>
        <p>Each day groups all scheduled subjects together for easier scanning.</p>
    </div>
    <div class="action-box" style="pointer-events:none;">
        <h5>Check Room And Time</h5>
        <p>Every schedule card highlights room, start time, and end time clearly.</p>
    </div>
    <div class="action-box" style="pointer-events:none;">
        <h5>Updates Automatically</h5>
        <p>Your schedule refreshes from the currently assigned classes in your section.</p>
    </div>
</div>

@foreach($dayOrder as $day)
    @php $items = $grouped->get($day, collect())->sortBy('start_time'); @endphp
    <div class="card">
        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:center; margin-bottom:14px;">
            <div>
                <h4 style="margin-bottom:6px;">{{ $day }}</h4>
                <p style="color:#64748b; margin:0;">{{ $items->count() ? $items->count() . ' subject(s) scheduled.' : 'No classes scheduled for this day.' }}</p>
            </div>
            <div style="padding:10px 12px; border-radius:999px; background:#eef2ff; color:#3730a3; font-weight:700; font-size:12px;">{{ $items->count() }} item(s)</div>
        </div>

        @if($items->isEmpty())
            <div style="padding:18px; border:1px dashed #cbd5e1; border-radius:16px; color:#64748b; background:#f8fafc;">No class schedule for this day yet.</div>
        @else
            <div style="display:grid; gap:12px;">
                @foreach($items as $item)
                    <div style="padding:18px; border:1px solid #dbeafe; border-radius:16px; background:linear-gradient(135deg, #eff6ff, #ffffff);">
                        <div style="display:flex; justify-content:space-between; gap:14px; flex-wrap:wrap; align-items:flex-start;">
                            <div>
                                <div style="font-size:20px; font-weight:700; color:#0f172a;">{{ $item['subject_name'] ?? '-' }}</div>
                                <div style="margin-top:6px; color:#475569;">Room: {{ $item['room'] ?? '-' }}</div>
                            </div>
                            <div style="padding:8px 12px; border-radius:999px; background:#dbeafe; color:#1d4ed8; font-weight:700; font-size:13px;">{{ $item['day_of_week'] ?? '-' }}</div>
                        </div>
                        <div style="margin-top:14px; display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
                            <div style="padding:12px; border-radius:12px; background:#fff;"><strong>Start Time</strong><div style="margin-top:6px; color:#334155;">{{ \Carbon\Carbon::parse($item['start_time'])->format('h:i A') }}</div></div>
                            <div style="padding:12px; border-radius:12px; background:#fff;"><strong>End Time</strong><div style="margin-top:6px; color:#334155;">{{ \Carbon\Carbon::parse($item['end_time'])->format('h:i A') }}</div></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endforeach

<div class="card">
    <h4>Schedule Notes</h4>
    <ul class="mini-list">
        <li>Please follow your assigned class times strictly.</li>
        <li>Contact the registrar if your section or class schedule looks incorrect.</li>
        <li>Your schedule updates automatically once class assignments are finalized.</li>
    </ul>
</div>
@endsection
