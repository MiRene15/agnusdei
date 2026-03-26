@extends('layouts.cashier')

@section('title', 'Receive Payment')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Receive Payment</h4>
    <p>Choose Plan A, B, or C. The system computes the bill automatically, and the cashier only enters the cash received.</p>
</div>

@if(session('success'))<div class="card" style="border-left:4px solid #16a34a; color:#166534; margin-bottom:16px;">{{ session('success') }}</div>@endif
@if(session('error'))<div class="card" style="border-left:4px solid #dc2626; color:#991b1b; margin-bottom:16px;">{{ session('error') }}</div>@endif
@if($errors->any())
<div class="card" style="border-left:4px solid #f59e0b; color:#92400e; margin-bottom:16px;">
    <strong>Please fix the following:</strong>
    <ul style="margin:8px 0 0 18px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
</div>
@endif

@php
    $student = $tuition->student;
    $isShs = in_array(trim((string) ($student->grade_level ?? '')), ['Grade 11', 'Grade 12'], true);
    $voucherSubmitted = $student && $student->admission && $student->admission->requirements->where('requirement_name', 'SHS Voucher')->where('submitted', 1)->isNotEmpty();
@endphp

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap:16px; margin-bottom:18px;">
    <div class="card" style="background:linear-gradient(135deg, #fffaf5, #ffffff); border:1px solid #fed7aa;"><div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#9a3412;">Student</div><div style="font-size:24px; font-weight:700; color:#7c2d12; margin-top:6px;">{{ $student->first_name ?? '-' }} {{ $student->last_name ?? '' }}</div><div style="color:#9a3412; margin-top:6px;">{{ $student->student_number ?? '-' }} | {{ $tuition->school_year }}</div></div>
    <div class="card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;"><div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#166534;">Portal Status</div><div style="font-size:24px; font-weight:700; color:#14532d; margin-top:6px;">{{ $tuition->is_downpayment_cleared ? 'Unlocked' : 'Locked' }}</div><div style="color:#166534; margin-top:6px;">Remaining to unlock: PHP {{ number_format($remainingForUnlock, 2) }}</div></div>
    <div class="card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;"><div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#1d4ed8;">Cashiering Rule</div><div style="font-size:24px; font-weight:700; color:#1e3a8a; margin-top:6px;">Cash Only</div><div style="color:#1d4ed8; margin-top:6px;">The cashier only records physical cash received and system-computed application.</div></div>
</div>

<div class="grid-2">
    <div class="card">
        <h4 style="margin-bottom:14px;">Billing Snapshot</h4>
        <div style="display:grid; gap:12px;">
            <div><strong>LRN:</strong> {{ $student->lrn ?? '-' }}</div>
            <div><strong>Grade Level:</strong> {{ $student->grade_level ?? '-' }}{{ $student->shs_track ? ' | ' . $student->shs_track : '' }}</div>
            <div><strong>Current Plan:</strong> {{ strtoupper($selectedPlan === 'cash' ? 'Plan A' : ($selectedPlan === 'monthly' ? 'Plan B' : 'Plan C')) }}</div>
            <div><strong>Total Tuition:</strong> PHP {{ number_format($tuition->total_amount, 2) }}</div>
            <div><strong>Automatic Discount:</strong> {{ $tuition->discount_type ? ucwords(str_replace('_', ' ', $tuition->discount_type)) : 'None' }}</div>
            <div><strong>Discount Amount:</strong> PHP {{ number_format($tuition->discount_amount ?? 0, 2) }}</div>
            <div><strong>Approved Carryover:</strong> PHP {{ number_format($tuition->previous_balance, 2) }}</div>
            <div><strong>Total Due:</strong> PHP {{ number_format($tuition->total_due, 2) }}</div>
            <div><strong>Already Paid:</strong> PHP {{ number_format($tuition->paid_amount, 2) }}</div>
            <div><strong>Current Balance:</strong> PHP {{ number_format($tuition->balance, 2) }}</div>
            <div><strong>Voucher Status:</strong> {{ ucwords(str_replace('_', ' ', $tuition->voucher_status ?? 'not_applicable')) }}</div>
        </div>
    </div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Cash Receiving Form</h4>

        <div style="display:grid; gap:12px; margin-bottom:18px;">
            <div style="padding:14px; border:1px solid #dbeafe; border-radius:14px; background:#f8fbff;">
                <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#1d4ed8;">Plan Guide</div>
                <div style="color:#334155; margin-top:8px; line-height:1.7; font-size:14px;">
                    Plan A: full payment with automatic cash-plan tuition discount.<br>
                    Plan B: down payment first, then monthly tuition posting.<br>
                    Plan C: flexible installment amount suggested automatically.
                </div>
            </div>
        </div>

        <form action="{{ route('cashier.payments.store', $tuition->id) }}" method="POST" style="display:grid; gap:14px;" id="payment-form">
            @csrf
            <div>
                <label style="display:block; margin-bottom:6px; font-weight:600;">Payment Plan</label>
                <select name="payment_plan" id="payment_plan" class="form-control" style="width:100%;">
                    <option value="cash" {{ old('payment_plan', $selectedPlan) === 'cash' ? 'selected' : '' }}>Plan A - Full Cash Payment</option>
                    <option value="monthly" {{ old('payment_plan', $selectedPlan) === 'monthly' ? 'selected' : '' }}>Plan B - Monthly Schedule</option>
                    <option value="alternative" {{ old('payment_plan', $selectedPlan) === 'alternative' ? 'selected' : '' }}>Plan C - Flexible Installment</option>
                </select>
            </div>

            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:12px;">
                <div style="padding:14px; border:1px solid #dbeafe; border-radius:14px; background:#eff6ff;">
                    <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#1d4ed8;">Auto Label</div>
                    <div id="auto-label" style="margin-top:8px; font-size:20px; font-weight:700; color:#1e3a8a;">-</div>
                </div>
                <div style="padding:14px; border:1px solid #dcfce7; border-radius:14px; background:#f0fdf4;">
                    <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#166534;">Applied To Tuition</div>
                    <div id="applied-amount" style="margin-top:8px; font-size:20px; font-weight:700; color:#166534;">PHP 0.00</div>
                </div>
                <div style="padding:14px; border:1px solid #fef3c7; border-radius:14px; background:#fffbeb;">
                    <div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#b45309;">Cash Change</div>
                    <div id="change-preview" style="margin-top:8px; font-size:20px; font-weight:700; color:#92400e;">PHP 0.00</div>
                </div>
            </div>

            <div>
                <label style="display:block; margin-bottom:6px; font-weight:600;">Cash Received</label>
                <input type="number" step="0.01" min="1" name="cash_tendered" id="cash_tendered" value="{{ old('cash_tendered') }}" class="form-control" required>
                <small style="display:block; margin-top:6px; color:#64748b;">Enter only the actual money physically received by the cashier.</small>
            </div>

            <div>
                <label style="display:block; margin-bottom:6px; font-weight:600;">Cashier Notes</label>
                <input type="text" name="notes" value="{{ old('notes') }}" class="form-control" placeholder="Optional note for this cash transaction">
            </div>

            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <button type="submit" class="btn btn-primary">Post Cash Payment</button>
                <a href="{{ route('cashier.billing') }}" class="btn btn-outline">Back to Billing</a>
            </div>
        </form>
    </div>
</div>

@if($isShs)
<div class="card" style="margin-top:18px;">
    <h4 style="margin-bottom:14px;">SHS Voucher Verification</h4>
    <div style="display:grid; gap:10px;">
        <div><strong>Voucher Submitted:</strong> {{ $voucherSubmitted ? 'Yes' : 'No' }}</div>
        <div><strong>Registrar Confirmation:</strong> {{ $tuition->voucher_registrar_verified_at ? $tuition->voucher_registrar_verified_at->format('M d, Y h:i A') : 'Pending' }}</div>
        <div><strong>Cashier Confirmation:</strong> {{ $tuition->voucher_cashier_verified_at ? $tuition->voucher_cashier_verified_at->format('M d, Y h:i A') : 'Pending' }}</div>
        <div><strong>Voucher Status:</strong> {{ ucwords(str_replace('_', ' ', $tuition->voucher_status ?? 'not_applicable')) }}</div>
    </div>
    @if($voucherSubmitted && $tuition->voucher_status === 'registrar_verified' && !$tuition->voucher_cashier_verified_at)
        <form action="{{ route('cashier.payments.voucher', $tuition->id) }}" method="POST" style="margin-top:14px;">
            @csrf
            <button type="submit" class="btn btn-success">Verify SHS Voucher and Apply Credit</button>
        </form>
    @elseif(!$voucherSubmitted)
        <p style="margin-top:14px; color:#64748b;">Waiting for the student to submit the SHS voucher requirement.</p>
    @elseif(!$tuition->voucher_registrar_verified_at)
        <p style="margin-top:14px; color:#64748b;">Waiting for registrar confirmation before cashier verification.</p>
    @else
        <p style="margin-top:14px; color:#166534;">The SHS voucher has already been fully processed for this billing record.</p>
    @endif
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    const planInput = document.getElementById('payment_plan');
    const cashInput = document.getElementById('cash_tendered');
    const autoLabel = document.getElementById('auto-label');
    const appliedAmount = document.getElementById('applied-amount');
    const changePreview = document.getElementById('change-preview');
    const planOptions = @json($planOptions);

    function syncPaymentPreview() {
        const selected = planOptions[planInput.value] || planOptions.monthly;
        const amount = Number(selected.recommended_amount || 0);
        const cash = Number(cashInput.value || 0);
        autoLabel.textContent = selected.payment_label || 'Pending';
        appliedAmount.textContent = 'PHP ' + amount.toFixed(2);
        changePreview.textContent = 'PHP ' + Math.max(0, cash - amount).toFixed(2);
    }

    planInput.addEventListener('change', syncPaymentPreview);
    cashInput.addEventListener('input', syncPaymentPreview);
    syncPaymentPreview();
});
</script>
@endsection
