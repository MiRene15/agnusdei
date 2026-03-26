@extends('layouts.cashier')

@section('title', 'Receive Payment')

@section('content')
<div class="page-intro" style="margin-bottom:20px;">
    <h4>Receive Payment</h4>
    <p>Record face-to-face tuition payments, installments, purchases, or SHS voucher credit against the student billing record.</p>
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
    <div class="card" style="background:linear-gradient(135deg, #f0fdf4, #ffffff); border:1px solid #bbf7d0;"><div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#166534;">Unlock Status</div><div style="font-size:24px; font-weight:700; color:#14532d; margin-top:6px;">{{ $tuition->is_downpayment_cleared ? 'Unlocked' : 'Locked' }}</div><div style="color:#166534; margin-top:6px;">Remaining to unlock: PHP {{ number_format($remainingForUnlock, 2) }}</div></div>
    <div class="card" style="background:linear-gradient(135deg, #eff6ff, #ffffff); border:1px solid #bfdbfe;"><div style="font-size:12px; text-transform:uppercase; letter-spacing:.08em; color:#1d4ed8;">Monthly Due</div><div style="font-size:24px; font-weight:700; color:#1e3a8a; margin-top:6px;">Every 15th</div><div style="color:#1d4ed8; margin-top:6px;">Down payment minimum: PHP {{ number_format($tuition->down_payment_required, 2) }}</div></div>
</div>

<div class="grid-2">
    <div class="card">
        <h4 style="margin-bottom:14px;">Billing Snapshot</h4>
        <div style="display:grid; gap:12px;">
            <div><strong>LRN:</strong> {{ $student->lrn ?? '-' }}</div>
            <div><strong>Grade Level:</strong> {{ $student->grade_level ?? '-' }}{{ $student->shs_track ? ' | ' . $student->shs_track : '' }}</div>
            <div><strong>Payment Plan:</strong> {{ ucfirst($tuition->payment_plan ?? 'monthly') }}</div>
            <div><strong>Total Tuition:</strong> PHP {{ number_format($tuition->total_amount, 2) }}</div>
            <div><strong>Automatic Discount:</strong> {{ $tuition->discount_type ? ucwords(str_replace('_', ' ', $tuition->discount_type)) : 'None' }}</div>
            <div><strong>Discount Amount:</strong> PHP {{ number_format($tuition->discount_amount ?? 0, 2) }}</div>
            <div><strong>Approved Carryover:</strong> PHP {{ number_format($tuition->previous_balance, 2) }}</div>
            <div><strong>Total Due:</strong> PHP {{ number_format($tuition->total_due, 2) }}</div>
            <div><strong>Balance:</strong> PHP {{ number_format($tuition->balance, 2) }}</div>
            <div><strong>Voucher Status:</strong> {{ ucwords(str_replace('_', ' ', $tuition->voucher_status ?? 'not_applicable')) }}</div>
        </div>
    </div>

    <div class="card">
        <h4 style="margin-bottom:14px;">Payment Form</h4>
        <form action="{{ route('cashier.payments.store', $tuition->id) }}" method="POST" style="display:grid; gap:14px;" id="payment-form">
            @csrf
            <div><label style="display:block; margin-bottom:6px; font-weight:600;">Payment Plan</label><select name="payment_plan" id="payment_plan" class="form-control" style="width:100%;"><option value="monthly" {{ old('payment_plan', $tuition->payment_plan ?? 'monthly') === 'monthly' ? 'selected' : '' }}>Plan B - Monthly</option><option value="cash" {{ old('payment_plan', $tuition->payment_plan) === 'cash' ? 'selected' : '' }}>Plan A - Cash</option><option value="alternative" {{ old('payment_plan', $tuition->payment_plan) === 'alternative' ? 'selected' : '' }}>Plan C - Alternative</option></select><small style="display:block; margin-top:6px; color:#64748b;">If a student also qualifies for honors, the system keeps only the higher automatic tuition discount.</small></div>
            <div><label style="display:block; margin-bottom:6px; font-weight:600;">Payment Label</label><select name="payment_label" class="form-control" style="width:100%;"><option value="Down Payment" {{ old('payment_label') === 'Down Payment' ? 'selected' : '' }}>Down Payment</option><option value="Monthly Installment" {{ old('payment_label', 'Monthly Installment') === 'Monthly Installment' ? 'selected' : '' }}>Monthly Installment</option><option value="Full Payment" {{ old('payment_label') === 'Full Payment' ? 'selected' : '' }}>Full Payment</option><option value="School Purchase" {{ old('payment_label') === 'School Purchase' ? 'selected' : '' }}>School Purchase</option></select></div>
            <div><label style="display:block; margin-bottom:6px; font-weight:600;">Amount Applied to Tuition</label><input type="number" step="0.01" min="1" max="{{ $tuition->balance }}" name="amount" id="amount" value="{{ old('amount', $remainingForUnlock > 0 ? min($remainingForUnlock, $tuition->balance) : '') }}" class="form-control" required><small style="display:block; margin-top:6px; color:#64748b;">Monthly-plan accounts must clear at least PHP {{ number_format($tuition->down_payment_required, 2) }} to unlock the portal.</small></div>
            <div><label style="display:block; margin-bottom:6px; font-weight:600;">Payment Method</label><select name="payment_method" id="payment_method" class="form-control" required><option value="Cash" {{ old('payment_method', 'Cash') === 'Cash' ? 'selected' : '' }}>Cash</option><option value="GCash" {{ old('payment_method') === 'GCash' ? 'selected' : '' }}>GCash</option><option value="Bank Transfer" {{ old('payment_method') === 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option></select></div>
            <div id="cash-fields"><div><label style="display:block; margin-bottom:6px; font-weight:600;">Cash Tendered</label><input type="number" step="0.01" min="0" name="cash_tendered" id="cash_tendered" value="{{ old('cash_tendered') }}" class="form-control"></div><div style="margin-top:10px; color:#64748b; font-size:13px;">Change to return: <strong id="change-preview">PHP 0.00</strong></div></div>
            <div><label style="display:block; margin-bottom:6px; font-weight:600;">Notes</label><input type="text" name="notes" value="{{ old('notes') }}" class="form-control" placeholder="Optional cashier note or purchase detail"></div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;"><button type="submit" class="btn btn-primary">Post Payment</button><a href="{{ route('cashier.billing') }}" class="btn btn-outline">Back to Billing</a></div>
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
    const amountInput = document.getElementById('amount');
    const methodInput = document.getElementById('payment_method');
    const cashTenderedInput = document.getElementById('cash_tendered');
    const cashFields = document.getElementById('cash-fields');
    const changePreview = document.getElementById('change-preview');
    function syncCashView() {
        const isCash = methodInput.value === 'Cash';
        cashFields.style.display = isCash ? 'block' : 'none';
        const amount = parseFloat(amountInput.value || '0');
        const tendered = parseFloat(cashTenderedInput.value || '0');
        const change = Math.max(0, tendered - amount);
        changePreview.textContent = 'PHP ' + change.toFixed(2);
    }
    amountInput.addEventListener('input', syncCashView);
    methodInput.addEventListener('change', syncCashView);
    cashTenderedInput.addEventListener('input', syncCashView);
    syncCashView();
});
</script>
@endsection
