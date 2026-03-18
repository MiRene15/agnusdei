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
        <div class="stat-label">Outstanding Balance</div>
        <div class="stat-value">₱{{ number_format($totalOutstanding, 2) }}</div>
        <div class="stat-sub">Total unpaid balances</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Payment Count</div>
        <div class="stat-value">{{ $paymentCount }}</div>
        <div class="stat-sub">Number of payment transactions</div>
    </div>

    <div class="stat-card">
        <div class="stat-label">Billing Count</div>
        <div class="stat-value">{{ $billingCount }}</div>
        <div class="stat-sub">Number of billing records</div>
    </div>
</div>

<div class="card">
    <h4>Report Notes</h4>
    <ul class="mini-list">
        <li>All values are based on the current database records.</li>
        <li>Billing totals come from the tuition_fees table.</li>
        <li>Collected totals come from the payments table.</li>
        <li>This page is for monitoring and reporting only.</li>
    </ul>
</div>

@endsection