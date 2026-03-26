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
            <div>
                <div class="receipt-school">Agnus Dei School Systems</div>
                <div class="receipt-sub">Official Cash Receipt</div>
                <div class="receipt-meta">School Year: {{ $payment->tuitionFee->school_year ?? 'N/A' }}</div>
            </div>
            <div class="receipt-badge">
                <div class="receipt-meta-label">Receipt No.</div>
                <div class="receipt-badge-value">{{ $payment->receipt_number ?? '-' }}</div>
            </div>
        </div>

        <div class="receipt-grid">
            <div class="receipt-box">
                <div class="receipt-meta-label">Received From</div>
                <div class="receipt-box-value">{{ $student->first_name ?? '-' }} {{ $student->last_name ?? '' }}</div>
                <div class="receipt-meta">{{ $student->student_number ?? '-' }} | LRN: {{ $student->lrn ?? '-' }}</div>
            </div>
            <div class="receipt-box">
                <div class="receipt-meta-label">Posted By</div>
                <div class="receipt-box-value">{{ $payment->received_by ?? '-' }}</div>
                <div class="receipt-meta">{{ $payment->payment_date ? $payment->payment_date->format('M d, Y') : now()->format('M d, Y') }}</div>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount Applied</th>
                    <th>Cash Received</th>
                    <th>Change</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $payment->payment_label ?? 'Cash Payment' }}</strong>
                        <div class="receipt-meta">Reference: {{ $payment->reference_no ?? '-' }}</div>
                        @if($payment->notes)
                            <div class="receipt-meta">Notes: {{ $payment->notes }}</div>
                        @endif
                    </td>
                    <td>PHP {{ number_format((float) $payment->amount, 2) }}</td>
                    <td>PHP {{ number_format((float) ($payment->cash_tendered ?? $payment->amount), 2) }}</td>
                    <td>PHP {{ number_format((float) ($payment->change_amount ?? 0), 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="receipt-totals">
            <div><span>Payment Method</span><strong>Physical Cash</strong></div>
            <div><span>Balance After Posting</span><strong>PHP {{ number_format((float) ($payment->tuitionFee->balance ?? 0), 2) }}</strong></div>
        </div>

        <div class="receipt-footer">
            <div>
                <div class="receipt-sign-line"></div>
                <div class="receipt-meta-label">Cashier Signature</div>
            </div>
            <div>
                <div class="receipt-sign-line"></div>
                <div class="receipt-meta-label">Received By</div>
            </div>
        </div>
    </div>
</div>

<style>
.receipt-shell { display:flex; justify-content:center; }
.receipt-paper { width:80mm; min-height:210mm; background:#fffef8; border:1px solid #e5e7eb; border-radius:14px; padding:10mm 7mm; box-shadow:0 18px 38px rgba(15,23,42,0.10); }
.receipt-head { display:flex; flex-direction:column; gap:12px; border-bottom:1px dashed #94a3b8; padding-bottom:12px; }
.receipt-school { font-size:18px; font-weight:800; color:#0f172a; line-height:1.2; }
.receipt-sub { font-size:11px; color:#475569; margin-top:4px; text-transform:uppercase; letter-spacing:.12em; }
.receipt-badge { padding:10px; border-radius:12px; background:#eff6ff; border:1px solid #bfdbfe; }
.receipt-badge-value { font-size:14px; font-weight:800; color:#1e3a8a; margin-top:6px; word-break:break-word; }
.receipt-grid { display:grid; gap:10px; margin:12px 0; }
.receipt-box { padding:10px; border:1px solid #e2e8f0; border-radius:12px; background:#fff; }
.receipt-box-value { font-size:14px; font-weight:700; color:#0f172a; margin-top:4px; }
.receipt-meta, .receipt-meta-label { color:#64748b; font-size:11px; line-height:1.5; }
.receipt-table { width:100%; border-collapse:collapse; margin-top:8px; }
.receipt-table th, .receipt-table td { border:1px solid #dbe2ea; padding:8px; text-align:left; vertical-align:top; font-size:11px; }
.receipt-table thead th { background:#0f172a; color:#fff; font-size:10px; }
.receipt-totals { display:grid; gap:10px; margin-top:12px; }
.receipt-totals div { padding:10px; border-radius:12px; background:#f8fafc; border:1px solid #e2e8f0; display:flex; justify-content:space-between; gap:12px; font-size:11px; }
.receipt-totals span { color:#475569; }
.receipt-totals strong { color:#0f172a; text-align:right; }
.receipt-footer { margin-top:18px; display:grid; gap:18px; }
.receipt-sign-line { border-bottom:1px solid #0f172a; height:18px; margin-bottom:6px; }
@media print {
    @page { size: 80mm auto; margin: 4mm; }
    body { background:#fff; }
    .sidebar, .topbar, .page-intro, .quick-actions { display:none !important; }
    .main, .content { padding:0 !important; }
    .receipt-shell { display:block; }
    .receipt-paper { box-shadow:none; border:none; border-radius:0; width:72mm; min-height:auto; padding:0; }
}
</style>
@endsection
