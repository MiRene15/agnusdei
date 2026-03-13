@extends('layouts.student')

@section('title', 'Student Dashboard')

@section('content')

<div class="page-intro">
    <h4>Dashboard</h4>
    <p>View your enrollment status, subjects, grades, schedule, and tuition balance.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Enrollment Status</div>
        <div class="stat-value">{{ $student->status ?? 'N/A' }}</div>
        <div class="stat-sub">Current student status</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Subjects Enrolled</div>
        <div class="stat-value">{{ $enrollments->count() }}</div>
        <div class="stat-sub">Auto-assigned after sectioning</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Paid</div>
        <div class="stat-value">₱{{ number_format($tuition->paid_amount ?? 0, 2) }}</div>
        <div class="stat-sub">Tuition paid so far</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Balance Left</div>
        <div class="stat-value">₱{{ number_format($tuition->balance ?? 0, 2) }}</div>
        <div class="stat-sub">Remaining balance</div>
    </div>
</div>

<div class="card">
    <h4>Recent Monthly Payments</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_method ?? '-' }}</td>
                        <td>{{ $payment->reference_no ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Subjects</h4>
        <ul class="mini-list">
            @forelse($enrollments as $enrollment)
                <li>{{ $enrollment->class->subject->subject_name ?? '-' }}</li>
            @empty
                <li>No subjects assigned yet.</li>
            @endforelse
        </ul>
    </div>

    <div class="card">
        <h4>Schedule Preview</h4>
        <ul class="mini-list">
            @forelse($schedule as $item)
                <li>
                    {{ $item['subject_name'] }} - {{ $item['day_of_week'] }}
                    ({{ $item['start_time'] }} - {{ $item['end_time'] }})
                </li>
            @empty
                <li>No schedule available yet.</li>
            @endforelse
        </ul>
    </div>
</div>

@endsection