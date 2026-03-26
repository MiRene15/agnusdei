@extends('layouts.cashier')

@section('title', 'Receipt')

@section('content')
<div class="page-intro">
    <h4>Official Receipt</h4>
    <p>Review the transaction details and print a formal receipt for filing and handover.</p>
</div>

<div class="receipt-shell" id="receipt-card">
    <div class="receipt-paper">
        <div class="receipt-header">
            <div>
                <div class="school-name">AGNUS DEI SCHOOL SYSTEMS INC.</div>
                <div class="school-subtitle">Official Receipt</div>
                <div class="school-meta">Student Accounts and Collections Office</div>
            </div>
            <div class="receipt-meta">
                <div><span>Receipt No.</span><strong>{{ $payment->receipt_number }}</strong></div>
                <div><span>Reference No.</span><strong>{{ $payment->reference_no }}</strong></div>
                <div><span>Date</span><strong>{{ $payment->payment_date ? \Carbon\Carbon::parse($payment->payment_date)->format('M d, Y') : '-' }}</strong></div>
            </div>
        </div>

        <div class="receipt-grid">
            <div class="receipt-block">
                <h5>Student Information</h5>
                <p><strong>Name:</strong> {{ $payment->tuitionFee->student->first_name ?? '-' }} {{ $payment->tuitionFee->student->last_name ?? '' }}</p>
                <p><strong>Student Number:</strong> {{ $payment->tuitionFee->student->student_number ?? '-' }}</p>
                <p><strong>LRN:</strong> {{ $payment->tuitionFee->student->lrn ?? '-' }}</p>
                <p><strong>Grade Level:</strong> {{ $payment->tuitionFee->student->grade_level ?? '-' }}{{ $payment->tuitionFee->student->shs_track ? ' | ' . $payment->tuitionFee->student->shs_track : '' }}</p>
                <p><strong>School Year:</strong> {{ $payment->tuitionFee->school_year ?? '-' }}</p>
            </div>
            <div class="receipt-block">
                <h5>Payment Summary</h5>
                <p><strong>Transaction:</strong> {{ $payment->payment_label ?? 'Tuition Payment' }}</p>
                <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
                <p><strong>Cashier:</strong> {{ $payment->received_by }}</p>
                <p><strong>Amount Received:</strong> PHP {{ number_format($payment->amount, 2) }}</p>
                <p><strong>Cash Tendered:</strong> {{ $payment->cash_tendered ? 'PHP ' . number_format($payment->cash_tendered, 2) : '-' }}</p>
                <p><strong>Change Returned:</strong> {{ $payment->change_amount ? 'PHP ' . number_format($payment->change_amount, 2) : '-' }}</p>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $payment->payment_label ?? 'Tuition Payment' }}</td>
                    <td>PHP {{ number_format($payment->amount, 2) }}</td>
                </tr>
                <tr>
                    <td>Balance After Payment</td>
                    <td>PHP {{ number_format($payment->tuitionFee->balance ?? 0, 2) }}</td>
                </tr>
                @if($payment->notes)
                <tr>
                    <td>Notes</td>
                    <td>{{ $payment->notes }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        <div class="receipt-footer">
            <div class="signature-box">
                <span>Received By</span>
                <strong>{{ $payment->received_by }}</strong>
            </div>
            <div class="signature-box">
                <span>Payor Confirmation</span>
                <strong>______________________</strong>
            </div>
        </div>
    </div>
</div>

<div style="display:flex; gap:12px; flex-wrap:wrap; margin-top:16px;">
    <button type="button" class="btn btn-primary" onclick="window.print()">Print Receipt</button>
    <a href="{{ route('cashier.payments') }}" class="btn btn-outline">Back to Payments</a>
</div>

<style>
.receipt-shell { display: flex; justify-content: center; }
.receipt-paper { width: min(100%, 860px); background: #fff; border: 1px solid #cbd5e1; border-radius: 18px; padding: 32px; box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08); }
.receipt-header { display: flex; justify-content: space-between; gap: 24px; border-bottom: 2px solid #0f172a; padding-bottom: 18px; margin-bottom: 22px; }
.school-name { font-size: 24px; font-weight: 700; color: #0f172a; letter-spacing: .04em; }
.school-subtitle { font-size: 16px; font-weight: 600; color: #1e3a8a; margin-top: 6px; }
.school-meta { font-size: 13px; color: #475569; margin-top: 8px; }
.receipt-meta { display: grid; gap: 10px; min-width: 220px; }
.receipt-meta div { display: flex; justify-content: space-between; gap: 16px; font-size: 14px; }
.receipt-meta span { color: #475569; }
.receipt-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-bottom: 22px; }
.receipt-block { border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; }
.receipt-block h5 { margin-bottom: 12px; color: #0f172a; }
.receipt-block p { margin-bottom: 8px; font-size: 14px; color: #334155; }
.receipt-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
.receipt-table th, .receipt-table td { border: 1px solid #cbd5e1; padding: 12px; text-align: left; font-size: 14px; }
.receipt-table thead th { background: #eff6ff; color: #1e3a8a; }
.receipt-footer { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 22px; margin-top: 28px; }
.signature-box { border-top: 1px solid #94a3b8; padding-top: 12px; font-size: 14px; color: #334155; }
.signature-box span { display: block; margin-bottom: 10px; color: #64748b; }
@media print {
    .page-intro, .btn, a.btn, button { display: none !important; }
    body { background: #fff; }
    .receipt-paper { box-shadow: none; border: none; padding: 0; width: 100%; }
}
</style>
@endsection
