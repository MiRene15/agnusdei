@extends('layouts.student')

@section('title', 'Assessment')

@section('content')

<div class="page-intro">
    <h4>Assessment</h4>
    <p>Track your tuition, paid amount, quarterly estimate, payment references, and remaining balance.</p>
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
        <div class="stat-label">Total Tuition</div>
        <div class="stat-value" style="font-size:20px;">
            ₱{{ number_format($tuition->total_amount ?? 0, 2) }}
        </div>
        <div class="stat-sub">Total fees for the school year</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Paid Amount</div>
        <div class="stat-value" style="font-size:20px;">
            ₱{{ number_format($tuition->paid_amount ?? 0, 2) }}
        </div>
        <div class="stat-sub">Payments received so far</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Balance Left</div>
        <div class="stat-value" style="font-size:20px;">
            ₱{{ number_format($tuition->balance ?? 0, 2) }}
        </div>
        <div class="stat-sub">Remaining amount to be paid</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Quarterly Estimate</div>
        <div class="stat-value" style="font-size:20px;">
            ₱{{ number_format($quarterAmount ?? 0, 2) }}
        </div>
        <div class="stat-sub">Estimated payment per quarter</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">School Year</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $tuition->school_year ?? ($student->school_year ?? 'N/A') }}
        </div>
        <div class="stat-sub">Current billing period</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Due Date</div>
        <div class="stat-value" style="font-size:20px;">
            {{ $tuition && $tuition->due_date ? \Carbon\Carbon::parse($tuition->due_date)->format('M d, Y') : 'N/A' }}
        </div>
        <div class="stat-sub">Latest payment due date</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Status</div>
        <div class="stat-value" style="font-size:20px; text-transform:capitalize;">
            {{ $tuition->status ?? 'N/A' }}
        </div>
        <div class="stat-sub">Current assessment status</div>
    </div>
</div>

<div class="card">
    <h4>Payment Breakdown</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Reference No.</th>
                    <th>Method</th>
                    <th>Received By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-' }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->reference_no ?? '-' }}</td>
                        <td>{{ $payment->payment_method ?? 'Cash' }}</td>
                        <td>{{ $payment->received_by ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No payments recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h4>Assessment Notes</h4>
    <ul class="mini-list">
        <li>Payments are recorded for monitoring purposes only.</li>
        <li>All payments are received face to face and cash only.</li>
        <li>Please keep your official reference number for every payment.</li>
        <li>Balance updates automatically once payments are posted in the database.</li>
    </ul>
</div>

@endsection