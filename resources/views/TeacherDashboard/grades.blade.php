@extends('layouts.teacher')

@section('title', 'Grade Encoding')

@section('content')
<div class="page-intro">
    <h4>Grade Encoding</h4>
    <p>Pick one class at a time, search for a student quickly, and upload quarterly seatwork, quiz, and exam scores with automatic final-grade computation.</p>
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

@php
    $gradingPeriods = ['1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter'];
@endphp

<div class="stats-grid">
    <div class="stat-card"><div class="stat-label">Formula</div><div class="stat-value" style="font-size:20px;">30 / 30 / 40</div><div class="stat-sub">Seatwork, quiz, exam weights</div></div>
    <div class="stat-card"><div class="stat-label">Classes Ready</div><div class="stat-value">{{ $classes->count() }}</div><div class="stat-sub">Classes available for encoding</div></div>
    <div class="stat-card"><div class="stat-label">Selected Class</div><div class="stat-value" style="font-size:20px;">{{ $selectedClass?->section ?? 'None' }}</div><div class="stat-sub">{{ $selectedClass?->grade_level ?? 'Choose a class first' }}</div></div>
    <div class="stat-card"><div class="stat-label">Visible Students</div><div class="stat-value">{{ $enrollments->count() }}</div><div class="stat-sub">Filtered list for grade entry</div></div>
</div>

<div class="card" style="background:linear-gradient(135deg, #f8fbff, #ffffff); border:1px solid #dbeafe;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:14px; align-items:start;">
        <div>
            <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#1d4ed8;">Grade Encoding Event</div>
            <div style="font-size:22px; font-weight:700; color:{{ $gradeEncodingOpen ? '#166534' : '#991b1b' }}; margin-top:6px;">{{ $gradeEncodingOpen ? 'Open' : 'Closed' }}</div>
            <div style="color:#64748b; margin-top:6px;">Teachers can upload grades only while the admin enables the academic event `Grade Encoding Open`.</div>
        </div>
        <div>
            <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#1d4ed8;">PTC Requirement</div>
            <div style="font-size:22px; font-weight:700; color:{{ $ptcRequired ? '#92400e' : '#166534' }}; margin-top:6px;">{{ $ptcRequired ? 'Required Before Upload' : 'Not Required' }}</div>
            <div style="color:#64748b; margin-top:6px;">If `PTC Required` is enabled, only students marked as PTC-complete can be encoded.</div>
        </div>
    </div>
</div>

<div class="card">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:flex-start; margin-bottom:18px;">
        <div>
            <h4 style="margin-bottom:6px;">Class Picker</h4>
            <p style="color:#64748b; max-width:720px;">Choose the class you want to work on, then narrow the list by student name, LRN, or student number. Uploaded quarterly grades lock right away for safer records.</p>
        </div>
        <div style="padding:12px 14px; border-radius:16px; background:#eff6ff; color:#1d4ed8; font-weight:700;">{{ optional($selectedClass?->subject)->subject_code ?? 'No Class Selected' }}</div>
    </div>

    <form method="GET" action="{{ route('teacher.grades') }}" class="search-row" style="margin-bottom:18px;">
        <select name="class_id">
            @foreach($classes as $class)
                <option value="{{ $class->id }}" @selected((int) request('class_id', $selectedClass?->id) === (int) $class->id)>
                    {{ $class->grade_level }} - {{ $class->section }} - {{ $class->subject->subject_name ?? '-' }}
                </option>
            @endforeach
        </select>
        <input type="text" name="student_search" value="{{ $studentSearch }}" placeholder="Search student name, student number, or LRN">
        <button type="submit" class="btn btn-primary">Load Class</button>
        @if($studentSearch !== '' || request()->filled('class_id'))
            <a href="{{ route('teacher.grades', $selectedClass ? ['class_id' => $selectedClass->id] : []) }}" class="btn btn-outline">Clear Filter</a>
        @endif
    </form>

    <div class="quick-actions">
        @forelse($classes as $class)
            <a href="{{ route('teacher.grades', ['class_id' => $class->id]) }}" class="action-box" style="{{ $selectedClass && (int) $selectedClass->id === (int) $class->id ? 'border-color:#60a5fa; background:linear-gradient(135deg,#dbeafe,#eff6ff); box-shadow:0 16px 30px rgba(37,99,235,.12);' : '' }}">
                <h5>{{ $class->section }}</h5>
                <p>{{ $class->grade_level }} | {{ $class->subject->subject_code ?? '-' }}</p>
                <p>{{ $class->enrollments_count }} students ready</p>
            </a>
        @empty
            <div style="padding:18px; border:1px dashed #cbd5e1; border-radius:16px; color:#64748b; background:#f8fafc;">No classes available for grade encoding.</div>
        @endforelse
    </div>
</div>

@if($selectedClass)
    <div class="card">
        <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:flex-start; margin-bottom:14px;">
            <div>
                <h4 style="margin-bottom:6px;">{{ $selectedClass->subject->subject_name ?? '-' }} ({{ $selectedClass->subject->subject_code ?? '-' }})</h4>
                <p style="color:#64748b; margin:0;">{{ $selectedClass->grade_level }} | {{ $selectedClass->section }} | {{ $selectedClass->school_year }} | Room {{ $selectedClass->room ?? '-' }}</p>
            </div>
            <div style="display:grid; gap:10px; min-width:240px;">
                <div style="padding:12px 14px; border-radius:16px; background:#eff6ff; color:#1e3a8a; font-weight:700;">{{ $selectedClass->enrollments->count() }} total students in class</div>
                <div style="padding:12px 14px; border-radius:16px; background:#f8fafc; color:#475569; font-weight:600;">{{ $enrollments->count() }} shown after filter</div>
            </div>
        </div>

        @forelse($enrollments as $enrollment)
            <div style="border:1px solid #dbe7f5; border-radius:22px; overflow:hidden; margin-bottom:18px; background:#fff; box-shadow:0 14px 32px rgba(15,23,42,.05);">
                <div style="padding:20px; background:linear-gradient(135deg, #f8fbff, #ffffff); border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:center;">
                    <div>
                        <div style="font-size:19px; font-weight:700; color:#0f172a;">{{ $enrollment->student->last_name ?? '-' }}, {{ $enrollment->student->first_name ?? '' }}</div>
                        <div style="margin-top:6px; color:#64748b;">{{ $enrollment->student->student_number ?? '-' }} | LRN: {{ $enrollment->student->lrn ?? '-' }}</div>
                    </div>
                    @if($ptcRequired && !($enrollment->student->ptc_completed ?? false))
                        <div style="padding:10px 12px; border-radius:999px; background:#fef3c7; color:#92400e; font-weight:700; font-size:13px;">PTC Pending</div>
                    @else
                        <div style="padding:10px 12px; border-radius:999px; background:#dcfce7; color:#166534; font-weight:700; font-size:13px;">Ready For Encoding</div>
                    @endif
                </div>

                <div style="display:grid; gap:14px; padding:18px; background:#fbfdff;">
                    @foreach($gradingPeriods as $period)
                        @php
                            $existingGrade = $enrollment->grades->where('grading_period', $period)->first();
                        @endphp
                        <div style="border:1px solid #e2e8f0; border-radius:18px; background:#fff; padding:16px;">
                            <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center; margin-bottom:12px;">
                                <div>
                                    <div style="font-size:16px; font-weight:700; color:#0f172a;">{{ $period }}</div>
                                    <div style="font-size:13px; color:#64748b;">Encode once only. Uploaded grades automatically lock for record safety.</div>
                                </div>
                                @if($existingGrade)
                                    <div style="padding:10px 12px; border-radius:999px; background:#e0f2fe; color:#075985; font-weight:700; font-size:12px;">Uploaded and Locked</div>
                                @else
                                    <div style="padding:10px 12px; border-radius:999px; background:#eef2ff; color:#3730a3; font-weight:700; font-size:12px;">Ready to Upload</div>
                                @endif
                            </div>

                            @if($existingGrade)
                                <div class="grade-preview-grid">
                                    <div class="preview-pill"><span>Seatwork</span><strong>{{ number_format((float) $existingGrade->seatwork_score, 2) }}</strong></div>
                                    <div class="preview-pill"><span>Quiz</span><strong>{{ number_format((float) $existingGrade->quiz_score, 2) }}</strong></div>
                                    <div class="preview-pill"><span>Exam</span><strong>{{ number_format((float) $existingGrade->exam_score, 2) }}</strong></div>
                                    <div class="preview-pill preview-pill-primary"><span>Final Grade</span><strong>{{ number_format((float) ($existingGrade->final_grade ?? $existingGrade->grade), 2) }}</strong></div>
                                    <div class="preview-pill" style="grid-column: span 2;"><span>Remarks</span><strong>{{ $existingGrade->remarks ?? '-' }}</strong></div>
                                </div>
                            @else
                                <form method="POST" action="{{ route('teacher.grades.save') }}" class="grade-entry-form" data-grade-form>
                                    @csrf
                                    <input type="hidden" name="enrollment_id" value="{{ $enrollment->id }}">
                                    <input type="hidden" name="grading_period" value="{{ $period }}">
                                    <input type="number" step="0.01" min="0" max="100" name="seatwork_score" placeholder="Seatwork score" required data-score-input>
                                    <input type="number" step="0.01" min="0" max="100" name="quiz_score" placeholder="Quiz score" required data-score-input>
                                    <input type="number" step="0.01" min="0" max="100" name="exam_score" placeholder="Exam score" required data-score-input>
                                    <input type="text" value="0.00" readonly data-final-preview placeholder="Auto-computed">
                                    <input type="text" name="remarks" placeholder="Optional remarks">
                                    <button type="submit" class="btn btn-primary">Upload Grade</button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div style="padding:18px; border:1px dashed #cbd5e1; border-radius:16px; color:#64748b; background:#f8fafc;">No students matched this class filter yet.</div>
        @endforelse
    </div>
@endif

<style>
.grade-entry-form {
    display:grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap:12px;
    align-items:center;
}
.grade-entry-form input {
    width:100%;
    padding:13px 14px;
    border:1px solid #cbd5e1;
    border-radius:14px;
    background:linear-gradient(180deg, #ffffff, #f8fbff);
    font-family:inherit;
    font-size:14px;
    color:#0f172a;
    box-shadow:inset 0 1px 2px rgba(15, 23, 42, .04);
}
.grade-entry-form input:focus {
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37, 99, 235, .12);
}
.grade-entry-form input[readonly] {
    background:#eff6ff;
    font-weight:700;
    color:#1e3a8a;
}
.grade-preview-grid {
    display:grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap:12px;
}
.preview-pill {
    border-radius:16px;
    border:1px solid #dbe7f5;
    background:linear-gradient(180deg, #ffffff, #f8fbff);
    padding:14px;
    display:grid;
    gap:6px;
}
.preview-pill span {
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.08em;
    color:#64748b;
}
.preview-pill strong {
    font-size:18px;
    color:#0f172a;
}
.preview-pill-primary {
    background:linear-gradient(135deg, #dbeafe, #eff6ff);
    border-color:#93c5fd;
}
@media (max-width: 1200px) {
    .grade-entry-form,
    .grade-preview-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}
@media (max-width: 640px) {
    .grade-entry-form,
    .grade-preview-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-grade-form]').forEach(function (form) {
        const inputs = form.querySelectorAll('[data-score-input]');
        const preview = form.querySelector('[data-final-preview]');

        function updatePreview() {
            const seatwork = parseFloat(inputs[0].value) || 0;
            const quiz = parseFloat(inputs[1].value) || 0;
            const exam = parseFloat(inputs[2].value) || 0;
            const finalGrade = ((seatwork * 0.30) + (quiz * 0.30) + (exam * 0.40)).toFixed(2);
            preview.value = finalGrade;
        }

        inputs.forEach(function (input) {
            input.addEventListener('input', updatePreview);
        });

        updatePreview();
    });
});
</script>
@endsection
