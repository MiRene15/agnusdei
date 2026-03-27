@extends('layouts.student')

@section('title', 'Assessment')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Assessment</h4>
    <p>Track your computed yearly tuition, carryover, payment progress, and the requirements that unlock full student access.</p>
</div>

@if(session('error'))
    <div class="card" style="border-left:4px solid #dc2626; color:#991b1b; margin-bottom:16px;">{{ session('error') }}</div>
@endif

<div class="stats-grid">
    <div class="stat-card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;">
        <div class="stat-label" style="color:#9a3412;">Portal Access</div>
        <div class="stat-value" style="color:#7c2d12;">{{ ($tuition && $tuition->is_downpayment_cleared && ($student->portal_access_status === 'unlocked')) ? 'Unlocked' : 'Locked' }}</div>
        <div class="stat-sub" style="color:#9a3412;">Remaining to unlock: PHP {{ number_format($remainingForUnlock ?? 0, 2) }}</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;">
        <div class="stat-label" style="color:#166534;">Annual Tuition</div>
        <div class="stat-value" style="color:#14532d;">PHP {{ number_format($tuition->total_amount ?? 0, 2) }}</div>
        <div class="stat-sub" style="color:#166534;">{{ $student->grade_level ?? 'N/A' }}{{ $student->shs_track ? ' | ' . $student->shs_track : '' }}</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;">
        <div class="stat-label" style="color:#1d4ed8;">Carryover + Due</div>
        <div class="stat-value" style="color:#1e3a8a;">PHP {{ number_format($tuition->previous_balance ?? 0, 2) }}</div>
        <div class="stat-sub" style="color:#1d4ed8;">Total due: PHP {{ number_format($tuition->total_due ?? 0, 2) }}</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg, #faf5ff, #ffffff); border:1px solid #ddd6fe;">
        <div class="stat-label" style="color:#6d28d9;">Monthly Plan</div>
        <div class="stat-value" style="color:#5b21b6;">PHP {{ number_format($tuition->monthly_payment ?? 0, 2) }}</div>
        <div class="stat-sub" style="color:#6d28d9;">Required down payment: PHP {{ number_format($tuition->down_payment_required ?? 0, 2) }}</div>
    </div>
</div>

<div class="quick-actions" style="margin-bottom:24px;">
    <div class="action-box" style="pointer-events:none;">
        <h5>Unlock Requirement</h5>
        <p>Your dashboard stays limited until the required down payment is cleared by cashier.</p>
    </div>
    <div class="action-box" style="pointer-events:none;">
        <h5>Payment Plan</h5>
        <p>Assessment reflects the current plan saved into your tuition record for this school year.</p>
    </div>
    <div class="action-box" style="pointer-events:none;">
        <h5>Voucher And Carryover</h5>
        <p>Any approved voucher credit or previous balance carryover appears here automatically.</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <h4>Assessment Summary</h4>
        <div style="display:grid; gap:12px; color:#334155;">
            <div><strong>Student:</strong> {{ $student->first_name }} {{ $student->last_name }}</div>
            <div><strong>LRN:</strong> {{ $student->lrn ?? 'N/A' }}</div>
            <div><strong>School Year:</strong> {{ $tuition->school_year ?? ($student->school_year ?? 'N/A') }}</div>
            <div><strong>Payment Plan:</strong> {{ $tuition ? ucfirst($tuition->payment_plan ?? 'monthly') : 'Monthly' }}</div>
            <div><strong>Monthly Due Date:</strong> {{ $tuition && $tuition->due_date ? \Carbon\Carbon::parse($tuition->due_date)->format('M d, Y') : 'N/A' }}</div>
            <div><strong>Status:</strong> {{ ucfirst($tuition->status ?? 'pending') }}</div>
            <div><strong>Automatic Discount:</strong> {{ $tuition && $tuition->discount_type ? ucwords(str_replace('_', ' ', $tuition->discount_type)) . ' (PHP ' . number_format($tuition->discount_amount ?? 0, 2) . ')' : 'None' }}</div>
            <div><strong>Voucher Status:</strong> {{ $tuition ? ucwords(str_replace('_', ' ', $tuition->voucher_status ?? 'not_applicable')) : 'Not Applicable' }}</div>
        </div>
    </div>

    <div class="card">
        <h4>What Happens Next</h4>
        <ul class="mini-list">
            <li>Proceed to the cashier for down payment, installment, or full payment.</li>
            <li>Monthly-plan students must first clear at least PHP {{ number_format($tuition->down_payment_required ?? 0, 2) }} before dashboard access fully unlocks.</li>
            <li>Cash-plan students receive the 10 percent tuition discount only when the full discounted balance is paid upon enrollment.</li>
            <li>If a student qualifies for honors and cash-plan discounts, the system keeps only the higher tuition discount.</li>
            <li>Senior High voucher applicants must complete both registrar and cashier verification before voucher credit unlocks the portal.</li>
            <li>Approved leftover balances from a previous school year appear automatically in this assessment.</li>
        </ul>
    </div>
</div>

<div class="card">
    <h4>Payment History</h4>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Label</th>
                    <th>Amount</th>
                    <th>Receipt</th>
                    <th>Method</th>
                    <th>Received By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-' }}</td>
                        <td>{{ $payment->payment_label ?? 'Tuition Payment' }}</td>
                        <td>PHP {{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->receipt_number ?? $payment->reference_no ?? '-' }}</td>
                        <td>{{ $payment->payment_method ?? 'Cash' }}</td>
                        <td>{{ $payment->received_by ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#64748b;">No payments recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
