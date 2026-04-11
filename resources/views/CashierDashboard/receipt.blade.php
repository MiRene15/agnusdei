@extends('layouts.cashier')

@section('title', 'Printable Receipt')

@section('content')
<div class="page-intro">
    <h4>Receipt Preview</h4>
    <p>Use this printable receipt for formal release after a posted cash payment.</p>
</div>

<div class="quick-actions" style="margin-bottom:18px;">
    <button onclick="window.print()" class="btn btn-primary" type="button">Print Receipt</button>
    <a href="{{ route('cashier.payments') }}" class="btn btn-outline">Back to Payments</a>
</div>

@php
    $student = $payment->tuitionFee->student;
@endphp

<div class="receipt-shell">
    <div class="receipt-paper">
        <div class="receipt-head">
            <div class="receipt-center">
                <div class="receipt-school">AGNUS DEI SCHOOL SYSTEMS</div>
                <div class="receipt-sub">Official Cash Receipt</div>
                <div class="receipt-meta">School Year: {{ $payment->tuitionFee->school_year ?? 'N/A' }}</div>
                <div class="receipt-meta">Receipt No. {{ $payment->receipt_number ?? '-' }}</div>
            </div>
        </div>

        <div class="receipt-info">
            <div><span>Received From:</span><strong>{{ $student->first_name ?? '-' }} {{ $student->last_name ?? '' }}</strong></div>
            <div><span>Student No:</span><strong>{{ $student->student_number ?? '-' }}</strong></div>
            <div><span>LRN:</span><strong>{{ $student->lrn ?? '-' }}</strong></div>
            <div><span>Date:</span><strong>{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : now()->format('M d, Y') }}</strong></div>
            <div><span>Cashier:</span><strong>{{ $payment->received_by ?? '-' }}</strong></div>
            <div><span>Method:</span><strong>PHYSICAL CASH</strong></div>
        </div>

        <div class="receipt-divider">--------------------------------</div>

        <div class="receipt-line-items">
            <div class="receipt-line-head">
                <span>Description</span>
                <span>Amount</span>
            </div>
            <div class="receipt-line-body">
                <span>{{ $payment->payment_label ?? 'Cash Payment' }}</span>
                <strong>PHP {{ number_format((float) $payment->amount, 2) }}</strong>
            </div>
            <div class="receipt-meta">Reference: {{ $payment->reference_no ?? '-' }}</div>
            @if($payment->notes)
                <div class="receipt-meta">Notes: {{ $payment->notes }}</div>
            @endif
        </div>

        <div class="receipt-divider">--------------------------------</div>

        <div class="receipt-totals">
            <div><span>Amount Applied</span><strong>PHP {{ number_format((float) $payment->amount, 2) }}</strong></div>
            <div><span>Cash Received</span><strong>PHP {{ number_format((float) ($payment->cash_tendered ?? $payment->amount), 2) }}</strong></div>
            <div><span>Change</span><strong>PHP {{ number_format((float) ($payment->change_amount ?? 0), 2) }}</strong></div>
            <div><span>Balance After Posting</span><strong>PHP {{ number_format((float) ($payment->tuitionFee->balance ?? 0), 2) }}</strong></div>
        </div>

        <div class="receipt-divider">--------------------------------</div>

        <div class="receipt-footer">
            <div>Thank you for your payment.</div>
            <div>Please keep this receipt for your records.</div>
        </div>
    </div>
</div>

<style>
.receipt-shell { display:flex; justify-content:center; }
.receipt-paper { width:58mm; min-height:160mm; background:#fffef8; border:1px solid #d6d3d1; border-radius:8px; padding:6mm 4mm; box-shadow:0 18px 38px rgba(15,23,42,0.10); font-family:"Courier New", monospace; color:#111827; }
.receipt-head { display:flex; flex-direction:column; gap:8px; padding-bottom:8px; }
.receipt-center { text-align:center; }
.receipt-school { font-size:14px; font-weight:800; line-height:1.35; letter-spacing:.04em; }
.receipt-sub { font-size:10px; margin-top:3px; text-transform:uppercase; letter-spacing:.18em; }
.receipt-info { display:grid; gap:5px; margin:10px 0; font-size:11px; }
.receipt-info div, .receipt-line-head, .receipt-line-body, .receipt-totals div { display:flex; justify-content:space-between; gap:8px; }
.receipt-info span, .receipt-line-head span, .receipt-meta { color:#4b5563; }
.receipt-divider { margin:10px 0 8px; text-align:center; color:#6b7280; font-size:11px; letter-spacing:.06em; }
.receipt-line-items { display:grid; gap:5px; font-size:11px; }
.receipt-line-head { font-weight:700; text-transform:uppercase; }
.receipt-line-body strong { text-align:right; }
.receipt-meta { font-size:10px; line-height:1.45; word-break:break-word; }
.receipt-totals { display:grid; gap:10px; margin-top:12px; }
.receipt-totals div { font-size:11px; }
.receipt-totals span { color:#4b5563; }
.receipt-totals strong { color:#0f172a; text-align:right; }
.receipt-footer { margin-top:16px; display:grid; gap:6px; text-align:center; font-size:10px; color:#374151; }
.receipt-shell { justify-content:center; margin-top:20px; }
@media screen {
    .sidebar, .topbar { display:none !important; }
    .main { width:100%; }
    .content { padding:20px 24px 30px; }
    .receipt-shell { display:flex; justify-content:center; margin-top:0; }
}
@media print {
    @page { size: 58mm auto; margin: 3mm; }
    body { background:#fff; }
    .sidebar, .topbar, .page-intro, .quick-actions { display:none !important; }
    .main, .content { padding:0 !important; }
    .receipt-shell { display:block; }
    .receipt-paper { box-shadow:none; border:none; border-radius:0; width:52mm; min-height:auto; padding:0; }
}
</style>
@endsection
