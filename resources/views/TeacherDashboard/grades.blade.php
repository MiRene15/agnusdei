@extends('layouts.teacher')

@section('title', 'Grade Encoding')

@section('content')
<div class="page-intro">
    <h4>Grade Encoding</h4>
    <p>Encode seatwork, quiz, and exam scores per quarter. Final grade is computed automatically using 30% seatwork, 30% quiz, and 40% exam.</p>
</div>

@if(session('success'))
    <div class="card" style="border-left:4px solid #16a34a; color:#166534;">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b;">{{ session('error') }}</div>
@endif

@if($errors->any())
    <div class="card" style="border-left:4px solid #f59e0b; color:#92400e;">
        <strong>Please fix the following:</strong>
        <ul style="margin:8px 0 0 18px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Formula</div><div class="stat-value" style="font-size:20px;">30 / 30 / 40</div><div class="stat-sub">Seatwork, Quiz, Exam weights</div></div>
    <div class="stat-card"><div class="stat-label">Classes Ready</div><div class="stat-value">{{ $classes->count() }}</div><div class="stat-sub">Classes available for encoding</div></div>
    <div class="stat-card"><div class="stat-label">Quarter Setup</div><div class="stat-value" style="font-size:20px;">4 Quarters</div><div class="stat-sub">1st to 4th grading periods</div></div>
</div>

@php
    $gradingPeriods = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
@endphp

@forelse($classes as $class)
    <div class="card">
        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:flex-start; margin-bottom:14px;">
            <div>
                <h4 style="margin-bottom:6px;">{{ $class->subject->subject_name ?? '-' }} ({{ $class->subject->subject_code ?? '-' }})</h4>
                <p style="color:#64748b; margin:0;">{{ $class->grade_level }} | {{ $class->section }} | {{ $class->school_year }}</p>
            </div>
            <div style="padding:12px 14px; border-radius:14px; background:#eff6ff; color:#1e3a8a; font-weight:700;">{{ $class->enrollments->count() }} Students</div>
        </div>

        <div style="display:grid; gap:18px;">
            @forelse($class->enrollments as $enrollment)
                <div style="border:1px solid #e2e8f0; border-radius:18px; overflow:hidden;">
                    <div style="padding:18px; background:linear-gradient(135deg, #f8fafc, #ffffff); border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:center;">
                        <div>
                            <div style="font-size:19px; font-weight:700; color:#0f172a;">{{ $enrollment->student->first_name ?? '-' }} {{ $enrollment->student->last_name ?? '' }}</div>
                            <div style="margin-top:6px; color:#64748b;">{{ $enrollment->student->student_number ?? '-' }} | LRN: {{ $enrollment->student->lrn ?? '-' }}</div>
                        </div>
                        @if(\App\Models\AcademicEvent::enabled('ptc_required') && !($enrollment->student->ptc_completed ?? false))
                            <div style="padding:10px 12px; border-radius:999px; background:#fef3c7; color:#92400e; font-weight:700; font-size:13px;">PTC Pending</div>
                        @else
                            <div style="padding:10px 12px; border-radius:999px; background:#dcfce7; color:#166534; font-weight:700; font-size:13px;">Ready For Encoding</div>
                        @endif
                    </div>

                    <div class="table-wrap" style="margin:0;">
                        <table style="min-width:1200px; margin:0;">
                            <thead>
                                <tr>
                                    <th>Quarter</th>
                                    <th>Seatwork (30%)</th>
                                    <th>Quiz (30%)</th>
                                    <th>Exam (40%)</th>
                                    <th>Computed Final</th>
                                    <th>Remarks</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gradingPeriods as $period)
                                    @php
                                        $existingGrade = $enrollment->grades->where('grading_period', $period)->first();
                                    @endphp
                                    <tr>
                                        <td style="font-weight:700; color:#0f172a;">{{ $period }}</td>
                                        <td colspan="6" style="padding:0;">
                                            <form method="POST" action="{{ route('teacher.grades.save') }}" style="display:grid; grid-template-columns: 170px 170px 170px 160px 1fr auto; gap:10px; padding:12px; align-items:center;">
                                                @csrf
                                                <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                                <input type="hidden" name="grading_period" value="{{ $period }}">

                                                <input type="number" step="0.01" min="0" max="100" name="seatwork_score" value="{{ $existingGrade->seatwork_score ?? '' }}" placeholder="Seatwork score" required style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;">
                                                <input type="number" step="0.01" min="0" max="100" name="quiz_score" value="{{ $existingGrade->quiz_score ?? '' }}" placeholder="Quiz score" required style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;">
                                                <input type="number" step="0.01" min="0" max="100" name="exam_score" value="{{ $existingGrade->exam_score ?? '' }}" placeholder="Exam score" required style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;">
                                                <input type="text" value="{{ $existingGrade->final_grade ?? $existingGrade->grade ?? '' }}" placeholder="Auto-computed" readonly style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px; background:#f8fafc; font-weight:700; color:#0f172a;">
                                                <input type="text" name="remarks" value="{{ $existingGrade->remarks ?? '' }}" placeholder="Optional remarks" style="padding:10px 12px; border:1px solid #cbd5e1; border-radius:10px;">
                                                <button type="submit" class="btn btn-primary">Save</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div style="padding:18px; border:1px dashed #cbd5e1; border-radius:16px; color:#64748b; background:#f8fafc;">No enrolled students found.</div>
            @endforelse
        </div>
    </div>
@empty
    <div class="card"><p style="color:#64748b;">No classes available for grade encoding.</p></div>
@endforelse
@endsection
