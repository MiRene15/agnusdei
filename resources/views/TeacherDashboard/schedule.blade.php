@extends('layouts.teacher')

@section('title', 'Teaching Schedule')

@section('content')
@php
    $dayOrder = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $grouped = collect($schedules)->sortBy(fn ($schedule) => array_search($schedule['day_of_week'], $dayOrder, true))->groupBy('day_of_week');
@endphp

<div class="page-intro">
    <h4>Teaching Schedule</h4>
    <p>View your weekly load in an easier day-by-day layout, with advisory classes clearly highlighted.</p>
</div>

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Total Schedule Blocks</div><div class="stat-value">{{ collect($schedules)->count() }}</div><div class="stat-sub">All assigned teaching periods</div></div>
    <div class="stat-card"><div class="stat-label">Teaching Days</div><div class="stat-value">{{ $grouped->count() }}</div><div class="stat-sub">Days with active classroom load</div></div>
    <div class="stat-card"><div class="stat-label">Advisory Blocks</div><div class="stat-value">{{ collect($schedules)->where('is_advisory', true)->count() }}</div><div class="stat-sub">Classes marked as advisory</div></div>
</div>

@forelse($dayOrder as $day)
    @php $items = $grouped->get($day, collect())->sortBy('start_time'); @endphp
    <div class="card">
        <h4 style="margin-bottom:6px;">{{ $day }}</h4>
        <p style="color:#64748b; margin-bottom:14px;">{{ $items->count() ? $items->count() . ' class block(s) scheduled.' : 'No assigned classes for this day.' }}</p>

        @if($items->isEmpty())
            <div style="padding:18px; border:1px dashed #cbd5e1; border-radius:16px; color:#64748b; background:#f8fafc;">Quiet day. No classroom blocks scheduled.</div>
        @else
            <div style="display:grid; gap:12px;">
                @foreach($items as $schedule)
                    <div style="padding:18px; border:1px solid {{ !empty($schedule['is_advisory']) ? '#bfdbfe' : '#e2e8f0' }}; border-radius:16px; background:{{ !empty($schedule['is_advisory']) ? 'linear-gradient(135deg, #eff6ff, #ffffff)' : '#ffffff' }};">
                        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:flex-start;">
                            <div>
                                <div style="font-size:20px; font-weight:700; color:#0f172a;">{{ $schedule['subject_name'] }}</div>
                                <div style="margin-top:6px; color:#475569;">{{ $schedule['subject_code'] }} | {{ $schedule['grade_level'] }} - {{ $schedule['section'] }}</div>
                            </div>
                            <div style="padding:8px 12px; border-radius:999px; background:{{ !empty($schedule['is_advisory']) ? '#dbeafe' : '#f1f5f9' }}; color:{{ !empty($schedule['is_advisory']) ? '#1d4ed8' : '#475569' }}; font-weight:700; font-size:13px;">{{ !empty($schedule['is_advisory']) ? 'Advisory Class' : 'Regular Class' }}</div>
                        </div>
                        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px; margin-top:14px;">
                            <div style="padding:12px; border-radius:12px; background:#f8fafc;"><strong>Time</strong><div style="margin-top:6px; color:#334155;">{{ \Carbon\Carbon::parse($schedule['start_time'])->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule['end_time'])->format('h:i A') }}</div></div>
                            <div style="padding:12px; border-radius:12px; background:#f8fafc;"><strong>Room</strong><div style="margin-top:6px; color:#334155;">{{ $schedule['room'] ?? '-' }}</div></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@empty
    <div class="card"><p style="color:#64748b;">No schedule found.</p></div>
@endforelse
@endsection
