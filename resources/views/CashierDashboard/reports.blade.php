@extends('layouts.cashier')

@section('title', 'Reports')

@section('content')

<div class="page-intro">
    <h4>Cashier Reports</h4>
    <p>Summary of collection and billing performance.</p>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Collected</div>
        <div class="stat-value">₱{{ number_format($totalCollected, 2) }}</div>
        <div class="stat-sub">All recorded payment entries</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Payment Count</div>
        <div class="stat-value">{{ $paymentCount }}</div>
        <div class="stat-sub">Number of payment transactions</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Fully Paid Accounts</div>
        <div class="stat-value">{{ $fullyPaid }}</div>
        <div class="stat-sub">Billing accounts with zero balance</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Accounts With Balance</div>
        <div class="stat-value">{{ $withBalance }}</div>
        <div class="stat-sub">Billing accounts still unpaid or partial</div>
    </div>
</div>

<div class="card">
    <h4 style="margin-bottom:14px;">Report Notes</h4>
    <ul class="mini-list">
        <li>Collection totals come from recorded payments.</li>
        <li>Billing completion is based on the current tuition fee balance.</li>
        <li>Student portal access is unlocked after required down payment is cleared.</li>
    </ul>
</div>

@endsection