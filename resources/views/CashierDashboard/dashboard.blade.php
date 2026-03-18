@extends('layouts.cashier')

@section('title', 'Cashier Dashboard')

@section('content')

<div class="page-intro">
    <h4>Cashier Dashboard</h4>
    <p>View billing summaries, recent collections, and outstanding balances.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Students with Billing</div>
        <div class="stat-value">{{ $totalStudentsWithBilling }}</div>
        <div class="stat-sub">Students with tuition records</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Total Collected</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
        <div class="stat-sub">All recorded payments</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Outstanding Balance</div>
        <div class="stat-value">₱{{ number_format($totalOutstanding, 2) }}</div>
        <div class="stat-sub">Combined remaining balances</div>
    </div>
</div>

<div class="card">
    <h4>Recent Payments</h4>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Payment Date</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Reference</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $payment)
                    <tr>
                        <td>
                            {{ $payment->tuitionFee->student->first_name ?? '-' }}
                            {{ $payment->tuitionFee->student->last_name ?? '' }}
                        </td>
                        <td>{{ $payment->payment_date ?? '-' }}</td>
                        <td>₱{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->payment_method ?? '-' }}</td>
                        <td>{{ $payment->reference_no ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#64748b;">No recent payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection