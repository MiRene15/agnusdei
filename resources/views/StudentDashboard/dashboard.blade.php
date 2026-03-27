@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Student Dashboard</h4>
    <p>Review your classes, progress, schedule, and billing snapshot from one modernized workspace.</p>
</div>

<div class="card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
    <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap; align-items:center;">
        <div>
            <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#1d4ed8;">Student Snapshot</div>
            <div style="font-size:28px; font-weight:700; color:#1e3a8a; margin-top:6px;">{{ $student->first_name }} {{ $student->last_name }}</div>
            <div style="color:#475569; margin-top:8px;">{{ $student->grade_level ?? '-' }} | {{ $student->section ?? 'Pending section' }} | {{ $student->school_year ?? '-' }}</div>
        </div>
        <div style="display:grid; gap:8px; min-width:240px;">
            <div style="padding:12px 14px; border-radius:14px; background:#ffffff; border:1px solid #dbeafe; color:#1e3a8a; font-weight:700;">Student No: {{ $student->student_number ?? '-' }}</div>
            <div style="padding:12px 14px; border-radius:14px; background:#ffffff; border:1px solid #dbeafe; color:#1e3a8a; font-weight:700;">Portal Access: {{ ucfirst($student->portal_access_status ?? 'locked') }}</div>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;">
        <div class="stat-label" style="color:#9a3412;">Enrollment Status</div>
        <div class="stat-value" style="color:#7c2d12;">{{ strtoupper($student->status ?? '-') }}</div>
        <div class="stat-sub" style="color:#9a3412;">Section: {{ $student->section ?? 'Pending assignment' }}</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;">
        <div class="stat-label" style="color:#166534;">Subjects Enrolled</div>
        <div class="stat-value" style="color:#14532d;">{{ $enrollments->count() }}</div>
        <div class="stat-sub" style="color:#166534;">Classes aligned to your section and grade level</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
        <div class="stat-label" style="color:#1d4ed8;">Total Paid</div>
        <div class="stat-value" style="color:#1e3a8a;">PHP {{ number_format($tuition->paid_amount ?? 0, 2) }}</div>
        <div class="stat-sub" style="color:#1d4ed8;">Balance left: PHP {{ number_format($tuition->balance ?? 0, 2) }}</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #faf5ff, #ffffff); border:1px solid #ddd6fe;">
        <div class="stat-label" style="color:#6d28d9;">Average Final Grade</div>
        <div class="stat-value" style="color:#5b21b6;">{{ $grades->count() ? number_format($grades->avg('final_grade'), 2) : 'N/A' }}</div>
        <div class="stat-sub" style="color:#6d28d9;">Based on encoded subject final grades</div>
    </div>
</div>

<div class="card" style="background:linear-gradient(180deg, #ffffff, #f8fbff);">
    <h4 style="margin-bottom:14px;">Quick Actions</h4>
    <div class="quick-actions">
        <a href="{{ route('student.subjects') }}" class="action-box">
            <h5>View Subjects</h5>
            <p>Open {{ $enrollments->count() }} enrolled subject{{ $enrollments->count() === 1 ? '' : 's' }} aligned with your current section and class load.</p>
        </a>
        <a href="{{ route('student.schedule') }}" class="action-box">
            <h5>Check Schedule</h5>
            <p>Review {{ $schedule->count() }} weekly schedule item{{ $schedule->count() === 1 ? '' : 's' }} with cleaner day, time, and room details.</p>
        </a>
        <a href="{{ route('student.grades') }}" class="action-box">
            <h5>Open Grades</h5>
            <p>Check {{ $grades->count() }} encoded grade entr{{ $grades->count() === 1 ? 'y' : 'ies' }} and switch between quarter filters easily.</p>
        </a>
        <a href="{{ route('student.assessment') }}" class="action-box">
            <h5>Review Assessment</h5>
            <p>{{ $tuition ? 'See your PHP ' . number_format($tuition->balance ?? 0, 2) . ' remaining balance and full tuition summary.' : 'Open your billing page and review the latest tuition details.' }}</p>
        </a>
    </div>
</div>
<div class="grid-2">
    <div class="card">
        <h4>Recent Payments</h4>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments->take(5) as $payment)
                        <tr>
                            <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-' }}</td>
                            <td>PHP {{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td>{{ $payment->receipt_number ?? $payment->reference_no ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; color:#64748b;">No payments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px; flex-wrap:wrap; margin-bottom:14px;">
            <div>
                <h4 style="margin-bottom:6px;">Schedule Preview</h4>
                <p style="color:#64748b; font-size:13px;">Your upcoming class flow at a glance.</p>
            </div>
            <div style="padding:10px 12px; border-radius:14px; background:#eff6ff; color:#1d4ed8; font-weight:700; font-size:12px;">{{ $schedule->count() }} item{{ $schedule->count() === 1 ? '' : 's' }}</div>
        </div>
        <div class="stacked-preview">
            @forelse($schedule->take(6) as $item)
                <div class="preview-row">
                    <div>
                        <div class="preview-title">{{ $item['subject_name'] }}</div>
                        <div class="preview-sub">{{ $item['day_of_week'] }} | {{ $item['room'] ?: 'Room TBD' }}</div>
                    </div>
                    <div class="preview-meta">{{ $item['start_time'] }} - {{ $item['end_time'] }}</div>
                </div>
            @empty
                <div class="empty-preview">No schedule available yet. Your classes may still be under section assignment.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px; flex-wrap:wrap; margin-bottom:14px;">
            <div>
                <h4 style="margin-bottom:6px;">Current Subjects</h4>
                <p style="color:#64748b; font-size:13px;">Subjects currently under your enrollment record.</p>
            </div>
            <div style="padding:10px 12px; border-radius:14px; background:#f0fdf4; color:#166534; font-weight:700; font-size:12px;">{{ $enrollments->count() }} subject{{ $enrollments->count() === 1 ? '' : 's' }}</div>
        </div>
        <div class="stacked-preview">
            @forelse($enrollments as $enrollment)
                <div class="preview-row">
                    <div>
                        <div class="preview-title">{{ $enrollment->class->subject->subject_name ?? '-' }}</div>
                        <div class="preview-sub">{{ $enrollment->class->subject->subject_code ?? 'No code' }}</div>
                    </div>
                    <div class="preview-meta">{{ $student->section ?? 'Pending' }}</div>
                </div>
            @empty
                <div class="empty-preview">No subjects assigned yet.</div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px; flex-wrap:wrap; margin-bottom:14px;">
            <div>
                <h4 style="margin-bottom:6px;">Grade Snapshot</h4>
                <p style="color:#64748b; font-size:13px;">Recent final-grade highlights from your record.</p>
            </div>
            <div style="padding:10px 12px; border-radius:14px; background:#faf5ff; color:#6d28d9; font-weight:700; font-size:12px;">{{ $grades->take(6)->count() }} shown</div>
        </div>
        <div class="stacked-preview">
            @forelse($grades->take(6) as $grade)
                <div class="preview-row">
                    <div>
                        <div class="preview-title">{{ $grade->enrollment->class->subject->subject_name ?? '-' }}</div>
                        <div class="preview-sub">{{ $grade->remarks ?? 'No remark yet' }}</div>
                    </div>
                    <div class="preview-grade">{{ number_format($grade->final_grade ?? $grade->grade, 2) }}</div>
                </div>
            @empty
                <div class="empty-preview">No grades encoded yet.</div>
            @endforelse
        </div>
    </div>
</div>

<div class="card">
    <h4>What To Do Next</h4>
    <div class="quick-actions">
        <div class="action-box" style="pointer-events:none;">
            <h5>Check Assessment</h5>
            <p>Review the latest tuition total, balance, and payment updates from cashier.</p>
        </div>
        <div class="action-box" style="pointer-events:none;">
            <h5>Track Grades</h5>
            <p>Open the grades page to switch between all quarters or a specific grading period.</p>
        </div>
        <div class="action-box" style="pointer-events:none;">
            <h5>Review Schedule</h5>
            <p>Use the schedule page when you need the full weekly class layout beyond the preview.</p>
        </div>
    </div>
</div>

<style>
.stacked-preview {
    display:grid;
    gap:12px;
}
.preview-row {
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:14px;
    padding:16px 18px;
    border:1px solid #dbeafe;
    border-radius:18px;
    background:linear-gradient(180deg, #ffffff, #f8fbff);
}
.preview-title {
    font-weight:700;
    color:#0f172a;
    margin-bottom:4px;
}
.preview-sub {
    color:#64748b;
    font-size:13px;
    line-height:1.5;
}
.preview-meta {
    padding:9px 12px;
    border-radius:12px;
    background:#eff6ff;
    color:#1d4ed8;
    font-size:12px;
    font-weight:700;
    white-space:nowrap;
}
.preview-grade {
    min-width:72px;
    text-align:center;
    padding:10px 12px;
    border-radius:14px;
    background:#ede9fe;
    color:#5b21b6;
    font-size:14px;
    font-weight:700;
}
.empty-preview {
    padding:18px;
    border:1px dashed #cbd5e1;
    border-radius:18px;
    color:#64748b;
    background:#f8fbff;
}
@media (max-width: 640px) {
    .preview-row {
        flex-direction:column;
        align-items:flex-start;
    }
    .preview-meta,
    .preview-grade {
        min-width:unset;
    }
}
</style>
@endsection

