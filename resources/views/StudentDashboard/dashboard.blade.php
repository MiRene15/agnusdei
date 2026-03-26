@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Student Dashboard</h4>
    <p>Your account is active. Review your classes, progress, schedule, and billing snapshot in one place.</p>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:18px;">
    <div class="card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#9a3412;">Enrollment Status</div>
        <div style="font-size:26px; font-weight:700; color:#7c2d12; margin-top:6px;">{{ strtoupper($student->status ?? '-') }}</div>
        <div style="color:#9a3412; margin-top:6px;">Section: {{ $student->section ?? 'Pending assignment' }}</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#166534;">Subjects Enrolled</div>
        <div style="font-size:26px; font-weight:700; color:#14532d; margin-top:6px;">{{ $enrollments->count() }}</div>
        <div style="color:#166534; margin-top:6px;">Classes aligned to your section and grade level</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#1d4ed8;">Total Paid</div>
        <div style="font-size:26px; font-weight:700; color:#1e3a8a; margin-top:6px;">PHP {{ number_format($tuition->paid_amount ?? 0, 2) }}</div>
        <div style="color:#1d4ed8; margin-top:6px;">Balance left: PHP {{ number_format($tuition->balance ?? 0, 2) }}</div>
    </div>
    <div class="card" style="background:linear-gradient(135deg, #faf5ff, #ffffff); border:1px solid #ddd6fe;">
        <div style="font-size:12px; letter-spacing:.08em; text-transform:uppercase; color:#6d28d9;">Average Final Grade</div>
        <div style="font-size:26px; font-weight:700; color:#5b21b6; margin-top:6px;">
            {{ $grades->count() ? number_format($grades->avg('final_grade'), 2) : 'N/A' }}
        </div>
        <div style="color:#6d28d9; margin-top:6px;">Based on encoded subject final grades</div>
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
        <h4>Schedule Preview</h4>
        <ul class="mini-list">
            @forelse($schedule->take(6) as $item)
                <li>{{ $item['subject_name'] }} | {{ $item['day_of_week'] }} | {{ $item['start_time'] }} - {{ $item['end_time'] }} | {{ $item['room'] }}</li>
            @empty
                <li>No schedule available yet. Your classes may still be under section assignment.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Current Subjects</h4>
        <ul class="mini-list">
            @forelse($enrollments as $enrollment)
                <li>{{ $enrollment->class->subject->subject_name ?? '-' }}</li>
            @empty
                <li>No subjects assigned yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <h4>Grade Snapshot</h4>
        <ul class="mini-list">
            @forelse($grades->take(6) as $grade)
                <li>{{ $grade->enrollment->class->subject->subject_name ?? '-' }} | Final Grade: {{ number_format($grade->final_grade ?? $grade->grade, 2) }} | {{ $grade->remarks ?? '-' }}</li>
            @empty
                <li>No grades encoded yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
