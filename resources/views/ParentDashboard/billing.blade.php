@extends('layouts.parent')

@section('title', 'Child Billing')

@section('content')

<div class="page-intro">
    <h4>Child Billing</h4>
    <p>Check tuition balances and payment records for each linked student.</p>
</div>

@forelse($children as $child)
    @php
        $billing = $billingByChild[$child->id] ?? null;
        $payments = $paymentsByChild[$child->id] ?? collect();
    @endphp

    <div class="card">
        <h4>{{ $child->first_name }} {{ $child->last_name }}</h4>
        <p style="color:#64748b; margin-bottom:16px;">
            LRN: {{ $child->lrn ?? '-' }} |
            Grade Level: {{ $child->grade_level ?? '-' }} |
            Section: {{ $child->section ?? '-' }}
        </p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Tuition</div>
                <div class="stat-value" style="font-size:20px;">₱{{ number_format($billing->total_amount ?? 0, 2) }}</div>
                <div class="stat-sub">Current school year total</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Paid Amount</div>
                <div class="stat-value" style="font-size:20px;">₱{{ number_format($billing->paid_amount ?? 0, 2) }}</div>
                <div class="stat-sub">Recorded payment total</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Balance</div>
                <div class="stat-value" style="font-size:20px;">₱{{ number_format($billing->balance ?? 0, 2) }}</div>
                <div class="stat-sub">Remaining balance</div>
            </div>

            <div class="stat-card">
                <div class="stat-label">Status</div>
                <div class="stat-value" style="font-size:20px;">{{ ucfirst($billing->status ?? 'N/A') }}</div>
                <div class="stat-sub">Latest billing status</div>
            </div>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Payment Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Reference No.</th>
                        <th>Received By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->payment_date ?? '-' }}</td>
                            <td>₱{{ number_format($payment->amount, 2) }}</td>
                            <td>{{ $payment->payment_method ?? '-' }}</td>
                            <td>{{ $payment->reference_no ?? '-' }}</td>
                            <td>{{ $payment->received_by ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; color:#64748b;">No payment records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@empty
    <div class="card">
        <p style="color:#64748b;">No linked children found.</p>
    </div>
@endforelse

@endsection